/*
 * gu-smart-select : transforme un <select> natif en liste déroulante avec recherche,
 * SANS dépendance et SANS casser le <select> sous-jacent (valeur + événement "change"
 * préservés, donc tout le code existant qui lit .val() / option:selected continue de marcher).
 *
 * Usage : ajouter l'attribut data-smart au <select>, puis appeler guSmartSelectAll().
 * Les <option> ajoutées dynamiquement après l'init sont lues à chaque ouverture.
 */
(function () {
    function enhance(select) {
        if (!select || select.dataset.guEnhanced === '1') return;
        select.dataset.guEnhanced = '1';
        select.style.display = 'none';

        var wrap = document.createElement('div');
        wrap.className = 'gu-ss';
        var control = document.createElement('button');
        control.type = 'button';
        control.className = 'gu-ss-control form-select';
        var panel = document.createElement('div');
        panel.className = 'gu-ss-panel';
        panel.innerHTML =
            '<div class="gu-ss-search"><i class="bx bx-search"></i><input type="text" autocomplete="off" placeholder="Rechercher…"></div>' +
            '<div class="gu-ss-list" role="listbox"></div>';
        wrap.appendChild(control);
        wrap.appendChild(panel);
        select.parentNode.insertBefore(wrap, select.nextSibling);

        var search = panel.querySelector('input');
        var list = panel.querySelector('.gu-ss-list');
        var activeIdx = -1;

        function selectedText() {
            var o = select.options[select.selectedIndex];
            if (o && o.value !== '' && !o.disabled) return o.textContent.trim();
            return '';
        }
        function placeholder() {
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value === '' || select.options[i].disabled) return select.options[i].textContent.trim();
            }
            return 'Sélectionner…';
        }
        function paint() {
            var txt = selectedText();
            control.innerHTML = '<span class="' + (txt ? '' : 'gu-ss-ph') + '">' + escapeHtml(txt || placeholder()) + '</span>';
        }
        function escapeHtml(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }

        function render(filter) {
            filter = (filter || '').toLowerCase();
            list.innerHTML = '';
            activeIdx = -1;
            var shown = [];
            for (var i = 0; i < select.options.length; i++) {
                var o = select.options[i];
                if (o.disabled || o.value === '') continue;
                var txt = o.textContent.trim();
                if (filter && txt.toLowerCase().indexOf(filter) === -1) continue;
                var item = document.createElement('div');
                item.className = 'gu-ss-item' + (i === select.selectedIndex ? ' selected' : '');
                item.setAttribute('data-i', i);
                item.textContent = txt;
                item.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    choose(parseInt(this.getAttribute('data-i'), 10));
                });
                list.appendChild(item);
                shown.push(item);
            }
            if (!shown.length) list.innerHTML = '<div class="gu-ss-empty">Aucun résultat</div>';
            return shown;
        }
        function choose(i) {
            select.selectedIndex = i;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            paint();
            close();
            control.focus();
        }
        var MARGIN = 8; // marge de securite par rapport au bord de la fenetre
        function position() {
            var rect = control.getBoundingClientRect();
            var spaceBelow = window.innerHeight - rect.bottom - MARGIN;
            var spaceAbove = rect.top - MARGIN;
            var openUp = spaceBelow < 180 && spaceAbove > spaceBelow;

            panel.style.left = rect.left + 'px';
            panel.style.width = rect.width + 'px';
            if (openUp) {
                panel.style.top = '';
                panel.style.bottom = (window.innerHeight - rect.top + 4) + 'px';
                panel.style.maxHeight = Math.max(120, spaceAbove) + 'px';
            } else {
                panel.style.bottom = '';
                panel.style.top = (rect.bottom + 4) + 'px';
                panel.style.maxHeight = Math.max(120, spaceBelow) + 'px';
            }
        }
        function open() {
            if (select.disabled) return;
            wrap.classList.add('open');
            search.value = '';
            render('');
            position();
            setTimeout(function () { search.focus(); }, 0);
        }
        function close() { wrap.classList.remove('open'); }

        control.addEventListener('click', function (e) { e.preventDefault(); wrap.classList.contains('open') ? close() : open(); });
        // Le panneau est en position:fixed (pour echapper a tout ancetre overflow:hidden) :
        // il faut le refermer si la PAGE bouge sous lui (sinon mal aligne), mais pas quand
        // le scroll vient de l'interieur du panneau lui-meme (liste de resultats).
        window.addEventListener('scroll', function (e) {
            if (wrap.classList.contains('open') && !wrap.contains(e.target)) close();
        }, true);
        window.addEventListener('resize', function () { if (wrap.classList.contains('open')) close(); });
        search.addEventListener('input', function () { render(search.value); });
        search.addEventListener('keydown', function (e) {
            var items = list.querySelectorAll('.gu-ss-item');
            if (e.key === 'Escape') { close(); control.focus(); }
            else if (e.key === 'ArrowDown') { e.preventDefault(); activeIdx = Math.min(items.length - 1, activeIdx + 1); highlight(items); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); activeIdx = Math.max(0, activeIdx - 1); highlight(items); }
            else if (e.key === 'Enter') { e.preventDefault(); if (items[activeIdx]) choose(parseInt(items[activeIdx].getAttribute('data-i'), 10)); }
        });
        function highlight(items) {
            items.forEach(function (it, k) { it.classList.toggle('kb', k === activeIdx); });
            if (items[activeIdx]) items[activeIdx].scrollIntoView({ block: 'nearest' });
        }
        document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });
        // refléter les changements programmatiques (reset, sélection par le code)
        select.addEventListener('change', paint);

        paint();
    }

    window.guSmartSelect = enhance;
    window.guSmartSelectAll = function (root) {
        (root || document).querySelectorAll('select[data-smart]').forEach(enhance);
    };
})();
