<main class="d-flex justify-content-center py-3 min-vh-100">

    <div id="horarios" class="container-fluid px-4" style="max-width: 420px;"
         data-base-url="<?= site_url() ?>">

        <?php
        $horariosOk = $this->session->flashdata('horarios_ok');
        $horariosError = $this->session->flashdata('horarios_error');
        ?>

        <?php if ($horariosOk): ?>
            <div class="alert alert-success" role="status">
                <?= html_escape($horariosOk) ?>
            </div>
        <?php endif; ?>

        <?php if ($horariosError): ?>
            <div class="alert alert-danger" role="alert">
                <?= html_escape($horariosError) ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">

            <!-- Navegación de semana -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <button type="button" id="semana-anterior"
                        class="btn btn-link text-white p-0"
                        aria-label="Semana anterior">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>

                <p class="text-secondary mb-0" id="semana-titulo">
                    Cargando…
                </p>

                <button type="button" id="semana-siguiente"
                        class="btn btn-link text-white p-0"
                        aria-label="Semana siguiente">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Tabs de días: los genera assets/js/horarios.js -->
            <ul class="nav nav-tabs border-0 justify-content-between"
                id="dias-tab" role="tablist"></ul>
        </div>

        <!-- Un solo tab-content: los paneles los genera el JS -->
        <div class="tab-content mt-4" id="dias-contenido"></div>

        <!-- Botones -->
        <div class="d-grid mt-4">
            <a href="<?= base_url('index.php/administrador/crearBloque') ?>"
               class="btn btn-primary py-3 rounded-4">
                + NUEVO BLOQUE
            </a>
        </div>

        <div class="d-grid mt-4">
            <button type="button" class="btn btn-outline-primary py-3 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#modal-generar">
                + GENERAR HORARIOS SEMANALES
            </button>
        </div>

    </div>

</main>

<!-- Modal: generar horarios semanales -->
<div class="modal fade" id="modal-generar" tabindex="-1"
     aria-labelledby="generar-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content plan-card">

            <?= form_open('administrador/generarHorarios') ?>

            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="generar-titulo">
                    Generar horarios semanales
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-white">
                <input type="hidden" name="token_bloque"
                       value="<?= html_escape($tokenBloque) ?>">

                <p class="mb-0">
                    Se ejecutará la generación automática de bloques horarios.
                    ¿Quieres continuar?
                </p>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary fw-bold">
                    Sí, generar
                </button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>
