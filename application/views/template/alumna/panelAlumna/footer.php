<!-- Bottom Navigation -->
<nav class="mobile-nav" aria-label="Navegación inferior">

    <ul class="nav nav-tabs d-flex justify-content-between text-center m-0 w-100" id="dashboardTabs" role="tablist">
        <li class="nav-item flex-fill p-0" role="presentation">
            <button class="nav-link active" id="inicio-tab" data-bs-toggle="tab" data-bs-target="#inicio" type="button"
                role="tab" aria-controls="inicio" aria-selected="true">

                <i class="bi bi-house-door-fill d-block mb-1"></i>
                <small>Inicio</small>

            </button>
        </li>

        <li class="nav-item flex-fill p-0" role="presentation">
            <button class="nav-link" id="agenda-tab" data-bs-toggle="tab" data-bs-target="#agenda" type="button"
                role="tab" aria-controls="agenda" aria-selected="false">

                <i class="bi bi-calendar-event d-block mb-1"></i>
                <small>Agendar</small>

            </button>
        </li>

        <li class="nav-item flex-fill p-0" role="presentation">
            <button class="nav-link" id="clases-tab" data-bs-toggle="tab" data-bs-target="#clases" type="button"
                role="tab" aria-controls="clases" aria-selected="false">

                <i class="bi bi-grid d-block mb-1"></i>
                <small>Clases</small>

            </button>
        </li>

        <li class="nav-item flex-fill p-0" role="presentation">
            <button class="nav-link" id="perfil-tab" data-bs-toggle="tab" data-bs-target="#perfil" type="button"
                role="tab" aria-controls="perfil" aria-selected="false">

                <i class="bi bi-person-square d-block mb-1"></i>
                <small>Perfil</small>

            </button>
        </li>

    </ul>

</nav>

<script src="<?= base_url('/assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('/assets/sweetalert2/js/sweetalert2@11.js') ?>"></script>
<script src="<?= base_url('/assets/js/alumna/horarios.js') ?>"></script>
<script src="<?= base_url('/assets/js/alumna/misclases.js') ?>"></script>
<script src="<?= base_url('/assets/js/alumna/navegacion.js') ?>"></script>
<script src="<?= base_url('/assets/js/alumna/btn.js') ?>"></script>


</body>

</html>