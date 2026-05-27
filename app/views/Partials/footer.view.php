<!-- BEGIN: Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/vendors.min.js"></script>
<script src="<?= ROOT ?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
<script src="<?= ROOT ?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
<script src="<?= ROOT ?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
<script src="<?= ROOT ?>/assets/fonts/fontawesome/js/fontawesome.js"></script>
<script src="<?= ROOT ?>/assets/fonts/fontawesome/js/solid.js"></script>
<!-- BEGIN Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<!-- BEGIN: Page Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/extensions/polyfill.min.js"></script>

<!-- BEGIN: Page Vendor JS-->
<script src="<?= ROOT ?>/assets/vendors/js/extensions/jquery.steps.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<!-- END: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<script src="<?= ROOT ?>/assets/vendors/js/forms/select/select2.full.min.js"></script>
<!-- BEGIN: Theme JS-->
<script src="<?= ROOT ?>/assets/js/core/app-menu.js"></script>
<script src="<?= ROOT ?>/assets/js/core/app.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/components.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/footer.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/print.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/html2pdf.bundle.min.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="<?= ROOT ?>/assets/js/scripts/forms/validation/form-validation.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>



<script src="<?= ROOT ?>/assets/js/scripts/forms/select/form-select2.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/extensions/sweet-alerts.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/forms/bsStepper.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/forms/custom-bsStepper.min.js"></script>

<!-- END: Page JS-->


<!-- END: Page JS-->
<!-- Ajout des scripts nécessaires pour faire fonctionner le modal -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<?php if (isset($_SESSION['id_utilisateur'])): ?>
<div class="gu-chatbot" data-gu-chatbot data-endpoint="<?= ROOT ?>/Assistants/chat" data-bot-name="G_universiteBot">
    <section class="gu-chatbot__panel" data-gu-chat-panel aria-label="Assistant IA G_UNIVERSITE">
        <header class="gu-chatbot__header">
            <div class="gu-chatbot__identity">
                <span class="gu-chatbot__title">G_universiteBot</span>
                <span class="gu-chatbot__subtitle">Assistant d'utilisation</span>
            </div>
            <div class="gu-chatbot__actions">
                <button class="gu-chatbot__icon-btn" type="button" data-gu-chat-clear title="Effacer la conversation" aria-label="Effacer la conversation">
                    <i class="bx bx-trash"></i>
                </button>
                <button class="gu-chatbot__icon-btn" type="button" data-gu-chat-close title="Reduire" aria-label="Reduire">
                    <i class="bx bx-minus"></i>
                </button>
            </div>
        </header>
        <div class="gu-chatbot__messages" data-gu-chat-messages>
            <div class="gu-chatbot__typing" data-gu-chat-typing aria-live="polite" aria-label="G_universiteBot ecrit">
                <span class="gu-chatbot__dot"></span>
                <span class="gu-chatbot__dot"></span>
                <span class="gu-chatbot__dot"></span>
            </div>
        </div>
        <form class="gu-chatbot__form" data-gu-chat-form>
            <textarea class="gu-chatbot__input" data-gu-chat-input rows="1" placeholder="Posez votre question..."></textarea>
            <button class="gu-chatbot__send" data-gu-chat-send type="submit" title="Envoyer" aria-label="Envoyer">
                <i class="bx bx-send"></i>
            </button>
        </form>
    </section>
    <button class="gu-chatbot__toggle" type="button" data-gu-chat-toggle aria-expanded="false" title="Ouvrir l'assistant IA" aria-label="Ouvrir l'assistant IA">
        <i class="bx bx-message-rounded-dots"></i>
    </button>
</div>
<script src="<?= ROOT ?>/assets/js/g-universite-chatbot.js"></script>
<?php endif; ?>
