<main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid px-4" style="max-width: 420px;">

        <div class="text-center mb-4">
            <h2 class="text-white fw-bold">
                Editar Bloque Horario
            </h2>
        </div>

        <?php $bloqueError = $this->session->flashdata('bloque_error'); ?>

        <?php if ($bloqueError): ?>
            <div class="alert alert-danger" role="alert">
                <?= html_escape($bloqueError) ?>
            </div>
        <?php endif; ?>

        <?= form_open('administrador/guardarBloque') ?>

            <input type="hidden" name="token_bloque"
                   value="<?= html_escape($tokenBloque) ?>">

            <input type="hidden" name="id_bloque"
                   value="<?= (int) $bloque->id_bloque ?>">

            <div class="mb-4">
                <label for="hora-inicio" class="form-label text-white">
                    Hora
                </label>

                <input id="hora-inicio" name="hora_inicio" type="time"
                       class="form-control custom-input"
                       value="<?= html_escape(substr((string) $bloque->hora_inicio, 0, 5)) ?>"
                       required>
            </div>

            <div class="mb-4">
                <label for="hora-termino" class="form-label text-white">
                    Hora de término
                </label>

                <input id="hora-termino" name="hora_termino" type="time"
                       class="form-control custom-input"
                       value="<?= html_escape(substr((string) $bloque->hora_termino, 0, 5)) ?>"
                       required>
            </div>

            <div class="mb-4">
                <label for="clase" class="form-label text-white">
                    Clase
                </label>

                <select id="clase" class="form-select custom-input">
                    <option>Grupal</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="profesor" class="form-label text-white">
                    Profesor
                </label>

                <select id="profesor" name="rut_profesor"
                        class="form-select custom-input" required>
                    <?php foreach ($profesores as $p): ?>
                        <option value="<?= html_escape($p->rut) ?>"
                            <?= $p->rut === $bloque->rut_profesor ? 'selected' : '' ?>>
                            <?= html_escape(trim($p->nombre . ' ' . $p->apellido)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha" class="form-label text-white">
                    Fecha
                </label>

                <input id="fecha" name="fecha" type="date"
                       class="form-control custom-input"
                       value="<?= html_escape(substr((string) $bloque->fecha, 0, 10)) ?>"
                       required>
            </div>

            <div class="mb-4">
                <label for="cupos" class="form-label text-white">
                    Cupos Máximos
                </label>

                <input id="cupos" name="cupos_maximos" type="number"
                       class="form-control custom-input"
                       min="<?= max(1, (int) $bloque->reservas) ?>"
                       max="999" step="1"
                       value="<?= (int) $bloque->cupos_maximos ?>"
                       required>

                <small class="text-secondary">
                    Reservas actuales: <?= (int) $bloque->reservas ?>
                </small>
            </div>

            <div class="d-grid gap-3 mt-5">

                <button type="submit"
                        class="btn btn-primary py-3 rounded-4 fw-bold">
                    GUARDAR CAMBIOS
                </button>

                <button type="button"
                        class="btn btn-outline-danger py-3 rounded-4 fw-bold"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-eliminar-bloque">
                    ELIMINAR BLOQUE
                </button>

            </div>

        <?= form_close() ?>

    </div>

</main>

<!-- Modal: eliminar bloque (fuera del formulario principal) -->
<div class="modal fade" id="modal-eliminar-bloque" tabindex="-1"
     aria-labelledby="eliminar-bloque-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content plan-card">

            <?= form_open('administrador/eliminarBloque') ?>

            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="eliminar-bloque-titulo">
                    Eliminar bloque
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-white">
                <input type="hidden" name="token_bloque"
                       value="<?= html_escape($tokenBloque) ?>">
                <input type="hidden" name="id_bloque"
                       value="<?= (int) $bloque->id_bloque ?>">

                <?php if ((int) $bloque->reservas > 0): ?>
                    <p class="mb-0">
                        Este bloque tiene
                        <strong><?= (int) $bloque->reservas ?></strong>
                        reserva(s) vigente(s), por lo que no se puede eliminar.
                    </p>
                <?php else: ?>
                    <p class="mb-2">
                        ¿Eliminar el bloque del
                        <strong><?= html_escape(substr((string) $bloque->fecha, 0, 10)) ?></strong>
                        a las
                        <strong><?= html_escape(substr((string) $bloque->hora_inicio, 0, 5)) ?></strong>?
                    </p>
                    <p class="mb-0">
                        Dejará de aparecer en los horarios. No se borran datos de la base.
                    </p>
                <?php endif; ?>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-danger fw-bold"
                        <?= (int) $bloque->reservas > 0 ? 'disabled' : '' ?>>
                    Sí, eliminar
                </button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>