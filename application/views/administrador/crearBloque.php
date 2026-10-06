<?php
$old = is_array($old ?? null) ? $old : [];
$v = function ($campo, $defecto = '') use ($old) {
    return html_escape($old[$campo] ?? $defecto);
};
?>
<main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid px-4" style="max-width: 420px;">

        <div class="text-center mb-4">
            <h2 class="text-white fw-bold">
                Crear Bloque Horario
            </h2>
        </div>

        <?php $bloqueError = $this->session->flashdata('bloque_error'); ?>

        <?php if ($bloqueError): ?>
            <div class="alert alert-danger" role="alert">
                <?= html_escape($bloqueError) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($profesores)): ?>
            <div class="alert alert-warning" role="alert">
                No hay profesores activos. Crea o reactiva un profesor antes de agregar un bloque.
            </div>
        <?php endif; ?>

        <?= form_open('administrador/guardarNuevoBloque') ?>

            <input type="hidden" name="token_bloque"
                   value="<?= html_escape($tokenBloque) ?>">

            <div class="mb-4">
                <label for="hora-inicio" class="form-label text-white">
                    Hora
                </label>

                <input id="hora-inicio" name="hora_inicio" type="time"
                       class="form-control custom-input"
                       value="<?= $v('hora_inicio', '09:00') ?>" required>
            </div>

            <div class="mb-4">
                <label for="hora-termino" class="form-label text-white">
                    Hora de término
                </label>

                <input id="hora-termino" name="hora_termino" type="time"
                       class="form-control custom-input"
                       value="<?= $v('hora_termino', '10:00') ?>" required>
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
                    <option value="" disabled
                        <?= empty($old['rut_profesor']) ? 'selected' : '' ?>>
                        Seleccionar profesor
                    </option>

                    <?php foreach ($profesores as $p): ?>
                        <option value="<?= html_escape($p->rut) ?>"
                            <?= ($old['rut_profesor'] ?? '') === $p->rut ? 'selected' : '' ?>>
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
                       min="<?= date('Y-m-d') ?>"
                       value="<?= $v('fecha', $fechaInicial) ?>" required>
            </div>

            <div class="mb-4">
                <label for="cupos" class="form-label text-white">
                    Cupos Máximos
                </label>

                <input id="cupos" name="cupos_maximos" type="number"
                       class="form-control custom-input"
                       min="1" max="999" step="1"
                       value="<?= $v('cupos_maximos', '15') ?>" required>
            </div>

            <div class="d-grid gap-3 mt-5">

                <button type="submit"
                        class="btn btn-primary py-3 rounded-4 fw-bold"
                        <?= empty($profesores) ? 'disabled' : '' ?>>
                    CREAR BLOQUE
                </button>

                <a href="<?= site_url('administrador/horarios') ?>"
                   class="btn btn-outline-light py-3 rounded-4 fw-bold">
                    CANCELAR
                </a>

            </div>

        <?= form_close() ?>

    </div>

</main>