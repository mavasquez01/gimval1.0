<!-- Bottom Navigation -->
    <nav class="mobile-nav" aria-label="Navegación inferior">

        <ul class="nav nav-tabs d-flex justify-content-between text-center m-0 w-100" id="dashboardTabs" role="tablist">
            <li class="nav-item flex-fill p-0" role="presentation">
                <button class="nav-link active" id="inicio-tab" data-bs-toggle="tab" data-bs-target="#inicio"
                    type="button" role="tab" aria-controls="inicio" aria-selected="true">

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
                <button class="nav-link" id="perfiL-tab" data-bs-toggle="tab" data-bs-target="#perfil" type="button"
                    role="tab" aria-controls="perfil" aria-selected="false">

                    <i class="bi bi-person-square d-block mb-1"></i>
                    <small>Perfil</small>

                </button>
            </li>

        </ul>

    </nav>

    <div class="modal fade" id="confirmarAgendamientoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-primary rounded-4">

                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        Clase agendada
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body text-center">

                    <i class="bi bi-check-circle-fill text-pink fs-1 mb-3"></i>

                    <h5 class="mb-2">
                        ¡Te inscribiste correctamente!
                    </h5>

                    <p class="text-secondary mb-0">
                        Tu clase de <span class="text-white">Funcional</span>
                        con <span class="text-white">Camila</span> quedó reservada para
                        el <span class="text-white">12 Mayo 2026 a las 09:00</span>.
                    </p>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
                        Entendido
                    </button>
                </div>

            </div>
        </div>
    </div>

<script src="<?= base_url('/assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>