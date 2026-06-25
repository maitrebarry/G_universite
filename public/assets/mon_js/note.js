// ============================================================
//  Saisie des notes — moteur (refonte : sauvegarde intelligente,
//  tableau de bord live, navigation clavier, recherche)
// ============================================================
var ROOT_NOTES = window.APP_ROUTE ? window.APP_ROUTE("Notes") : window.APP_ROOT + "/Notes";
var ROOT_EDT = window.APP_ROUTE ? window.APP_ROUTE("Emploi_du_temps") : window.APP_ROOT + "/Emploi_du_temps";

function toggleSessionFields(show, hideNotes) {
  var sessionInfo = document.getElementById("session_info");
  var tableSection = document.getElementById("table_section");
  if (sessionInfo) sessionInfo.classList.toggle("hidden", !show);
  if (tableSection) tableSection.classList.toggle("hidden", !show);
  document.querySelectorAll(".note_session").forEach(function (f) { f.classList.toggle("hidden", hideNotes); });
}

// ---- Calcul de la moyenne (session normale) ----
function calculeMoyenModuleSessionNormale(d, e, s) {
  d = isNaN(d) ? 0 : d; e = isNaN(e) ? 0 : e; s = isNaN(s) ? 0 : s;
  var m = (d + 2 * e) / 3;
  if (s > m) m = s;
  return m;
}
function classeMoyenne(m) { return m < 10 ? "danger" : (m < 15 ? "warning" : "success"); }

// ---- Helpers ligne ----
function valNote(row, sel) { var v = row.find(sel).val(); return v === "" ? NaN : parseFloat(v); }
function estSaisi(row) { return row.find(".noteDevoir").val() !== "" || row.find(".noteEvaluation").val() !== ""; }

function majLigne(row) {
  var badge = row.find(".moyenneBadge");
  if (!estSaisi(row)) { badge.text("—").attr("class", "moyenneBadge badge badge-soft-secondary"); return null; }
  var m = calculeMoyenModuleSessionNormale(valNote(row, ".noteDevoir"), valNote(row, ".noteEvaluation"), valNote(row, ".noteSession"));
  badge.text(m.toFixed(2)).attr("class", "moyenneBadge badge badge-soft-" + classeMoyenne(m));
  return m;
}

// ---- Tableau de bord live ----
function statCard(icon, big, label, color, sub) {
  return '<div class="gu-stat gu-stat-' + color + '"><i class="bx ' + icon + '"></i>' +
    '<div><div class="v">' + big + (sub ? ' <small>' + sub + '</small>' : '') + '</div>' +
    '<div class="l">' + label + '</div></div></div>';
}
function updateStats() {
  var box = document.getElementById("noteStats"); if (!box) return;
  var rows = $("#notesTable tbody tr"), total = rows.length, saisis = 0, somme = 0, admis = 0;
  rows.each(function () {
    var r = $(this);
    if (estSaisi(r)) {
      saisis++;
      var m = calculeMoyenModuleSessionNormale(valNote(r, ".noteDevoir"), valNote(r, ".noteEvaluation"), valNote(r, ".noteSession"));
      somme += m; if (m >= 10) admis++;
    }
  });
  var moy = saisis ? somme / saisis : 0, ajournes = saisis - admis;
  var taux = saisis ? Math.round((admis / saisis) * 100) : 0, pct = total ? Math.round((saisis / total) * 100) : 0;
  box.innerHTML =
    statCard("bx-list-check", saisis + "/" + total, "Notes saisies", "info", pct + "%") +
    statCard("bx-bar-chart-alt-2", saisis ? moy.toFixed(2) : "—", "Moyenne de la classe", saisis ? classeMoyenne(moy) : "secondary") +
    statCard("bx-check-circle", admis, "Admis (≥ 10)", "success") +
    statCard("bx-x-circle", ajournes, "Ajournés (< 10)", "danger") +
    statCard("bx-trophy", saisis ? taux + "%" : "—", "Taux de réussite", taux >= 50 ? "success" : "warning");
}

// ---- Sauvegarde intelligente (debounce + statut par ligne) ----
var saveTimers = {}, pendingCount = 0;
function setStatus(row, state) {
  var el = row.find(".saveStatus");
  if (state === "dirty") el.html('<span class="text-tertiary" title="Modifié"><i class="bx bx-pencil"></i></span>');
  else if (state === "saving") el.html('<span class="text-secondary" title="Enregistrement…"><i class="bx bx-loader-alt bx-spin"></i></span>');
  else if (state === "saved") el.html('<span class="text-success" title="Enregistré"><i class="bx bx-check-circle"></i></span>');
  else if (state === "error") el.html('<span class="text-danger" title="Échec — réessayez"><i class="bx bx-error-circle"></i></span>');
  else el.html("");
}
function majSaveAll(forceBusy) {
  var el = document.getElementById("saveAll"); if (!el) return;
  if (forceBusy || pendingCount > 0) { el.className = "gu-note-saveall busy"; el.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Enregistrement…'; }
  else { el.className = "gu-note-saveall ok"; el.innerHTML = '<i class="bx bx-check-circle"></i> Tout est enregistré'; }
}
function planifierSave(row) {
  var id = row.data("id");
  setStatus(row, "dirty"); majSaveAll(true);
  clearTimeout(saveTimers[id]);
  saveTimers[id] = setTimeout(function () { saveRow(row); }, 700);
}
function saveRow(row) {
  setStatus(row, "saving"); pendingCount++;
  var saisi = estSaisi(row);
  var m = saisi ? calculeMoyenModuleSessionNormale(valNote(row, ".noteDevoir"), valNote(row, ".noteEvaluation"), valNote(row, ".noteSession")) : "";
  $.ajax({
    url: ROOT_NOTES + "/save_note_etudiant", type: "POST", dataType: "json",
    data: {
      idNote: row.data("id"),
      devoir: row.find(".noteDevoir").val(),
      evaluation: row.find(".noteEvaluation").val(),
      session: row.find(".noteSession").val(),
      moyenne: m === "" ? "" : m.toFixed(2),
      action: "noterecuee",
    },
  }).done(function (res) { setStatus(row, res && res.ok ? "saved" : "error"); })
    .fail(function () { setStatus(row, "error"); })
    .always(function () { pendingCount--; majSaveAll(false); });
}

// ---- Navigation clavier (Entrée / flèches = ligne suivante, même colonne) ----
function navClavier(e) {
  if (e.key !== "Enter" && e.key !== "ArrowDown" && e.key !== "ArrowUp") return;
  e.preventDefault();
  var cls = e.target.classList.contains("noteDevoir") ? "noteDevoir"
    : e.target.classList.contains("noteEvaluation") ? "noteEvaluation" : "noteSession";
  var rows = $("#notesTable tbody tr:visible");
  var idx = rows.index($(e.target).closest("tr"));
  var next = (e.key === "ArrowUp") ? rows.eq(idx - 1) : rows.eq(idx + 1);
  if (next.length) { var inp = next.find("." + cls); inp.focus(); inp[0] && inp[0].select(); }
}

// ---- Recherche ----
function filtrerNotes() {
  var q = (document.getElementById("noteSearch").value || "").toLowerCase().trim();
  $("#notesTable tbody tr").each(function () {
    var r = $(this);
    var ok = !q || ("" + r.data("nom")).indexOf(q) > -1 || ("" + r.data("mat")).indexOf(q) > -1;
    r.toggle(ok);
  });
}

// ---- Initialisation après injection du tableau ----
function initNotesTable() {
  $("#notesTable tbody tr").each(function () { majLigne($(this)); });
  updateStats(); majSaveAll(false);

  $("#notesTable").off("input.note").on("input.note", ".note", function () {
    var v = parseFloat(this.value);
    if (v < 0) this.value = 0;
    if (v > 20) this.value = 20;
    var row = $(this).closest("tr");
    majLigne(row); updateStats(); planifierSave(row);
  });
  $("#notesTable").off("keydown.note").on("keydown.note", ".note", navClavier);

  var se = document.getElementById("noteSearch");
  if (se) se.addEventListener("input", filtrerNotes);
}

// ---- Chargement des étudiants + notes ----
function loadEtudiants(save) {
  var filiere_id, promotion_id, module_id, semestre_id;
  if (!save) {
    filiere_id = $("#promotions option:selected").data("filiere");
    promotion_id = $("#promotions option:selected").val();
    module_id = $("#modules").val();
    semestre_id = $("#promotions option:selected").data("semestre");
  } else {
    filiere_id = sessionStorage.getItem("filiere");
    promotion_id = sessionStorage.getItem("classe");
    module_id = sessionStorage.getItem("module");
    semestre_id = sessionStorage.getItem("semestre");
  }

  $("#loadingSpinner").show();
  $.ajax({
    url: ROOT_NOTES + "/get_note_etudiant", type: "POST",
    data: { idPromotion: promotion_id, idModule: module_id, idFiliere: filiere_id, idSemestre: semestre_id },
    success: function (response) {
      $("#loadingSpinner").hide();
      var t = (response || "").trim();
      if (t === "" || t === "notfound") {
        $("#table_section").html('<div class="gu-empty"><i class="bx bx-error-circle"></i>Vérifiez votre sélection (classe et module).</div>');
        return;
      }
      $("#table_section").html(response);
      if (document.getElementById("notesTable")) initNotesTable();
    },
    error: function (xhr, status, error) {
      $("#loadingSpinner").hide();
      console.error("Erreur AJAX : ", status, error);
      $("#table_section").html('<div class="gu-empty"><i class="bx bx-error-circle"></i>Erreur de chargement. Réessayez.</div>');
    },
  });
}

// ============================================================
//  Cascade : filière -> classes -> modules
// ============================================================
async function infosFiliere(idFiliere, source) {
  try {
    return await $.ajax({
      method: "POST", url: ROOT_EDT + "/filiere_info", dataType: "json",
      data: { source: source || null, idFiliere: idFiliere },
    });
  } catch (error) { console.error(error); }
}

function classesAnneeUniversitaire(anneeUniversitaire) {
  $.ajax({
    method: "POST", url: ROOT_NOTES + "/get_classe_annee", dataType: "json",
    data: { anneeUniversitaire: anneeUniversitaire },
    success: function (response) {
      var promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append('<option value="" disabled selected>Sélectionner une classe</option>');
      (response || []).forEach(function (promotion) {
        promotionContainer.append(
          "<option value='" + promotion.id_promotion + "' data-filiere='" + promotion.id_filiere +
          "' data-semestre='" + promotion.id_parcours + "'>" + promotion.classe + "</option>"
        );
      });
    },
    error: function () {},
  });
}

function modulesSemestre(idSemestre, infoFiliere, idModule) {
  var c = $("#modules");
  c.empty();
  c.append('<option value="" disabled selected>Sélectionner un module</option>');
  if (!infoFiliere || !infoFiliere.ues || !infoFiliere.modules) return;
  var selected = idModule || sessionStorage.getItem("module");
  infoFiliere.ues.forEach(function (ue) {
    if (ue.id_parcours == idSemestre) {
      infoFiliere.modules.forEach(function (module) {
        if (module.id_ue == ue.id_ue) {
          c.append("<option value='" + module.id_ue_module + "'" + (module.id_ue_module == selected ? " selected" : "") +
            ">" + module.nom_module + " (" + module.code_module + ")</option>");
        }
      });
    }
  });
}
