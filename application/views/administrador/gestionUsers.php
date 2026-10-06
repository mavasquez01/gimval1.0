<main class="d-flex justify-content-center min-vh-100 px-4">
    <div id="gestion-users" class="w-100" style="max-width: 420px;"
         data-base-url="<?= site_url() ?>"
         data-tab-inicial="<?= html_escape($tab) ?>">

        <div class="tab-content" id="gestion-contenido"></div>
    </div>
</main>

<script src="<?= base_url('assets/js/administrador/gestionUsers.js') ?>"></script>