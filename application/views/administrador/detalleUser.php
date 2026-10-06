<?php
/**
 * Botón + modal para desactivar / reactivar.
 * Se reutiliza en la vista de alumna y en la de profesor.
 */
$bloqueEstado = function ($tipo, $rut, $activo, $nombre) use ($tokenEstado) {
    $esAlumna = $tipo === 'alumna';
    $articulo = $esAlumna ? 'la alumna' : 'el profesor';
    $pronombre = $esAlumna ? 'reactivarla' : 'reactivarlo';
    ?>
    <button
        type="button"
        class="btn <?= $activo ? 'btn-outline-danger' : 'btn-outline-success' ?> w-100 mt-3 py-2 rounded-4 fw-bold"
        data-bs-toggle="modal"
        data-bs-target="#modal-estado">
        <i class="bi <?= $activo ? 'bi-person-x' : 'bi-person-check' ?>"
           aria-hidden="true"></i>
        <?= $activo ? 'Desactivar' : 'Reactivar' ?>
        <?= $esAlumna ? 'alumna' : 'profesor' ?>
    </button>

    <div class="modal fade" id="modal-estado" tabindex="-1"
         aria-labelledby="estado-titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content plan-card">

                <?= form_open('administrador/cambiarEstadoUsuario') ?>

                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="estado-titulo">
                        <?= $activo ? 'Desactivar' : 'Reactivar' ?>
                        <?= $esAlumna ? 'alumna' : 'profesor' ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body text-white">
                    <input type="hidden" name="token_estado"
                           value="<?= html_escape($tokenEstado) ?>">
                    <input type="hidden" name="tipo"
                           value="<?= html_escape($tipo) ?>">
                    <input type="hidden" name="rut"
                           value="<?= html_escape($rut) ?>">
                    <input type="hidden" name="activo"
                           value="<?= $activo ? '0' : '1' ?>">

                    <?php if ($activo): ?>
                        <p class="mb-2">
                            ¿Desactivar a <strong><?= html_escape($nombre) ?></strong>?
                            No podrá iniciar sesión mientras esté inactiv<?= $esAlumna ? 'a' : 'o' ?>.
                        </p>
                        <p class="mb-0">
                            No se borra ningún dato: podrás <?= $pronombre ?> cuando quieras.
                        </p>
                    <?php else: ?>
                        <p class="mb-0">
                            ¿Reactivar a <strong><?= html_escape($nombre) ?></strong>?
                            Podrá volver a iniciar sesión.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn <?= $activo ? 'btn-danger' : 'btn-success' ?> fw-bold">
                        <?= $activo ? 'Sí, desactivar' : 'Sí, reactivar' ?>
                    </button>
                </div>

                <?= form_close() ?>

            </div>
        </div>
    </div>
    <?php
};
?>
<?php if (isset($profesor)): ?>

    <main class="d-flex justify-content-center py-4 min-vh-100">
        <div class="container-fluid px-4" style="max-width: 500px;">

            <?php
            $estadoOk = $this->session->flashdata('estado_ok');
            $estadoError = $this->session->flashdata('estado_error');
            ?>

            <?php if ($estadoOk): ?>
                <div class="alert alert-success" role="status">
                    <?= html_escape($estadoOk) ?>
                </div>
            <?php endif; ?>

            <?php if ($estadoError): ?>
                <div class="alert alert-danger" role="alert">
                    <?= html_escape($estadoError) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body plan-card p-4">

                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-circle display-4 text-white"
                           aria-hidden="true"></i>

                        <div class="flex-grow-1" style="min-width: 0;">
                            <h4 class="text-white mb-2">
                                <?= html_escape(trim(
                                    $profesor->nombre . ' ' .
                                    $profesor->apellido
                                )) ?>
                            </h4>

                            <p class="text-white mb-1">
                                RUT: <?= html_escape($profesor->rut) ?>
                            </p>

                            <p class="text-white text-break mb-3">
                                <?= html_escape(
                                    $profesor->email ?? 'Sin correo registrado'
                                ) ?>
                            </p>

                            <div class="d-flex align-items-center gap-2">
                                <span class="text-white">Estado:</span>

                                <?php if ((int) $profesor->activo === 1): ?>
                                    <span class="badge bg-success">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php $bloqueEstado(
                        'profesor',
                        $profesor->rut,
                        (int) $profesor->activo === 1,
                        trim($profesor->nombre . ' ' . $profesor->apellido)
                    ); ?>

                </div>
            </div>

        </div>
    </main>

<?php else: ?>
<main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid px-4" style="max-width: 500px;">

        <!-- Datos de la alumna -->
        <div class="row ms-3">
            <div class="col-auto align-items-center">
                <img
                    src="<?= base_url('/assets/images/alumna1.jpg') ?>"
                    class="rounded-circle me-3 avatar-img-exp"
                    alt="Foto de perfil">
            </div>

            <div class="col align-items-center mt-3">
                <div class="row">
                    <h4 class="text-white mb-1">
                        <?= html_escape(trim(
                            $alumna->nombre . ' ' . $alumna->apellido
                        )) ?>
                    </h4>
                </div>

                <div class="row">
                    <p class="text-white mb-1">
                        RUT: <?= html_escape($alumna->rut) ?>
                    </p>
                </div>

                <div class="row">
                    <p class="text-white mb-1">
                        <?= html_escape(
                            $alumna->email ?? 'Sin correo registrado'
                        ) ?>
                    </p>
                </div>

                <div class="row">
                    <p class="text-white mb-1">
                        Estado:
                        <?php if ((int) $alumna->activo === 1): ?>
                            <span class="badge bg-success">Activa</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactiva</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Información del plan -->
        <div class="row mt-3 ms-3">
            <div class="col">
                <?php if ($plan): ?>
                    <?php
                    $coloresEstado = [
                        1 => 'bg-success',
                        2 => 'bg-danger',
                        3 => 'bg-warning text-dark'
                    ];

                    $colorEstado =
                        $coloresEstado[(int) $plan->id_estado_plan]
                        ?? 'bg-secondary';

                    $estado = $plan->nombre_estado
                        ?? 'Estado no disponible';

                    $fechaTexto = (string) $plan->fecha_termino;

                    $fechaFin = DateTimeImmutable::createFromFormat(
                        '!Y-m-d',
                        $fechaTexto
                    );

                    $fechaValida = $fechaFin
                        && $fechaFin->format('Y-m-d') === $fechaTexto;
                    ?>

                    <p class="text-white">
                        <?= html_escape(
                            $plan->nombre_plan ?? 'Sin nombre de plan'
                        ) ?>
                    </p>

                    <p class="text-white">
                        Estado:
                        <span class="badge <?= $colorEstado ?>">
                            <?= html_escape(ucfirst($estado)) ?>
                        </span>
                    </p>

                    <p class="text-white">
                        Fecha de vencimiento:
                        <?= $fechaValida
                            ? html_escape($fechaFin->format('d/m/Y'))
                            : 'Sin fecha válida' ?>
                    </p>

                    <p class="text-white">
                        <?= (int) $plan->clases_restantes ?>
                        clases restantes
                    </p>

                    <!-- Acciones rápidas sobre el plan -->
                    <div class="d-flex gap-2 mt-3">
                        <button
                            type="button"
                            class="btn btn-success flex-fill py-2 rounded-4 fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-renovar">
                            <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                            Renovar plan
                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-primary flex-fill py-2 rounded-4 fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-modificar">
                            <i class="bi bi-pencil" aria-hidden="true"></i>
                            Modificar plan
                        </button>
                    </div>
                <?php else: ?>
                    <p class="text-white">
                        Sin plan registrado.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resultado de las acciones sobre el plan -->
        <?php
        $extensionOk = $this->session->flashdata('extension_ok');
        $extensionError = $this->session->flashdata('extension_error');
        ?>

        <?php if ($extensionOk): ?>
            <div class="alert alert-success mt-3" role="status">
                <?= html_escape($extensionOk) ?>
            </div>
        <?php endif; ?>

        <?php if ($extensionError): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= html_escape($extensionError) ?>
            </div>
        <?php endif; ?>

        <!-- Tarjeta original para extender el plan -->
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body plan-card">
                        <div class="row align-items-center justify-content-center p-3">
                            <div class="col-auto">
                                <h5 class="text-white">
                                    Congelar plan por licencias médicas
                                </h5>

                                <?= form_open('administrador/extenderPlan') ?>

                                <input
                                    type="hidden"
                                    name="token_extension"
                                    value="<?= html_escape($tokenExtension) ?>">

                                <input
                                    type="hidden"
                                    name="rut"
                                    value="<?= html_escape($alumna->rut) ?>">

                                <input
                                    type="hidden"
                                    name="id_plan_alumna"
                                    value="<?= $plan ? (int) $plan->id_plan_alumna : '' ?>">

                                <input
                                    type="hidden"
                                    name="fecha_anterior"
                                    value="<?= html_escape($plan ? $plan->fecha_termino : '') ?>">

                                <div class="mb-4">
                                    <label
                                        for="dias-extension"
                                        class="form-label text-white">
                                        Días a extender
                                    </label>

                                    <input
                                        id="dias-extension"
                                        name="dias"
                                        type="number"
                                        class="form-control custom-input"
                                        min="1"
                                        max="365"
                                        step="1"
                                        value="1"
                                        required
                                        <?= (!$plan || !$puedeExtender) ? 'disabled' : '' ?>>
                                </div>

                                <?php if (!$plan): ?>
                                    <p class="text-white">
                                        La alumna no tiene un plan para extender.
                                    </p>
                                <?php endif; ?>

                                <button
                                    type="submit"
                                    class="btn btn-primary py-3 rounded-4 fw-bold"
                                    <?= (!$plan || !$puedeExtender) ? 'disabled' : '' ?>>
                                    EXTENDER
                                </button>

                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado de activar / desactivar -->
        <?php
        $estadoOk = $this->session->flashdata('estado_ok');
        $estadoError = $this->session->flashdata('estado_error');
        ?>

        <?php if ($estadoOk): ?>
            <div class="alert alert-success mt-3" role="status">
                <?= html_escape($estadoOk) ?>
            </div>
        <?php endif; ?>

        <?php if ($estadoError): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= html_escape($estadoError) ?>
            </div>
        <?php endif; ?>

        <!-- Desactivar / reactivar alumna -->
        <?php $bloqueEstado(
            'alumna',
            $alumna->rut,
            (int) $alumna->activo === 1,
            trim($alumna->nombre . ' ' . $alumna->apellido)
        ); ?>

    </div>

</main>

<?php if ($plan): ?>

<!-- Modal: renovar plan -->
<div class="modal fade" id="modal-renovar" tabindex="-1"
     aria-labelledby="renovar-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content plan-card">

            <?= form_open('administrador/renovarPlan') ?>

            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="renovar-titulo">
                    Renovar plan
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-white">
                <input type="hidden" name="token_extension"
                       value="<?= html_escape($tokenExtension) ?>">
                <input type="hidden" name="rut"
                       value="<?= html_escape($alumna->rut) ?>">
                <input type="hidden" name="id_plan_alumna"
                       value="<?= (int) $plan->id_plan_alumna ?>">
                <input type="hidden" name="fecha_anterior"
                       value="<?= html_escape($plan->fecha_termino) ?>">

                <?php
                $clasesActuales = max(0, (int) $plan->clases_restantes);
                $clasesNuevas = (int) $plan->cantidad_clases;
                ?>

                <p class="mb-2">
                    Se creará un nuevo plan
                    <strong><?= html_escape($plan->nombre_plan ?? '') ?></strong>
                    de <?= (int) $duracionRenovacion ?> días con
                    <?= $clasesNuevas ?> clases, más las
                    <?= $clasesActuales ?> que le quedan:
                    <strong>total <?= $clasesActuales + $clasesNuevas ?> clases</strong>.
                </p>

                <p class="mb-0">
                    Comienza hoy. Si el plan actual sigue vigente, se le suman
                    los días que le quedan, y el plan actual pasa a vencido.
                </p>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-success fw-bold">
                    Confirmar renovación
                </button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>

<!-- Modal: modificar plan -->
<div class="modal fade" id="modal-modificar" tabindex="-1"
     aria-labelledby="modificar-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content plan-card">

            <?= form_open('administrador/modificarPlan') ?>

            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="modificar-titulo">
                    Modificar plan
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="token_extension"
                       value="<?= html_escape($tokenExtension) ?>">
                <input type="hidden" name="rut"
                       value="<?= html_escape($alumna->rut) ?>">
                <input type="hidden" name="id_plan_alumna"
                       value="<?= (int) $plan->id_plan_alumna ?>">
                <input type="hidden" name="fecha_anterior"
                       value="<?= html_escape($plan->fecha_termino) ?>">

                <div class="mb-3">
                    <label for="mod-plan" class="form-label text-white">
                        Plan
                    </label>
                    <select id="mod-plan" name="id_plan"
                            class="form-select custom-input" required>
                        <?php foreach ($planes as $p): ?>
                            <option value="<?= (int) $p->id_plan ?>"
                                data-clases="<?= (int) $p->cantidad_clases ?>"
                                <?= (int) $p->id_plan === (int) $plan->id_plan ? 'selected' : '' ?>>
                                <?= html_escape($p->nombre_plan) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="mod-inicio" class="form-label text-white">
                            Inicio
                        </label>
                        <input id="mod-inicio" name="fecha_inicio" type="date"
                               class="form-control custom-input"
                               value="<?= html_escape(substr($plan->fecha_inicio, 0, 10)) ?>"
                               required>
                    </div>

                    <div class="col-6">
                        <label for="mod-termino" class="form-label text-white">
                            Término
                        </label>
                        <input id="mod-termino" name="fecha_termino" type="date"
                               class="form-control custom-input"
                               value="<?= html_escape(substr($plan->fecha_termino, 0, 10)) ?>"
                               required>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label for="mod-clases" class="form-label text-white">
                            Clases restantes
                        </label>
                        <input id="mod-clases" name="clases_restantes"
                               type="number" min="0" max="999" step="1"
                               class="form-control custom-input"
                               value="<?= (int) $plan->clases_restantes ?>"
                               required>
                    </div>

                    <div class="col-6">
                        <label for="mod-estado" class="form-label text-white">
                            Estado
                        </label>
                        <select id="mod-estado" name="id_estado_plan"
                                class="form-select custom-input" required>
                            <?php foreach ($estadosPlan as $e): ?>
                                <option value="<?= (int) $e->id_estado_plan ?>"
                                    <?= (int) $e->id_estado_plan === (int) $plan->id_estado_plan ? 'selected' : '' ?>>
                                    <?= html_escape(ucfirst($e->nombre_estado)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary fw-bold">
                    Guardar cambios
                </button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>

<script>
(() => {
    const selPlan = document.getElementById('mod-plan');
    const inputClases = document.getElementById('mod-clases');
    if (!selPlan || !inputClases) return;

    const planInicial = selPlan.value;
    const clasesIniciales = parseInt(inputClases.value, 10) || 0;

    // Al cambiar de plan: clases actuales + clases del nuevo plan (editable)
    selPlan.addEventListener('change', () => {
        if (selPlan.value === planInicial) {
            inputClases.value = clasesIniciales;
            return;
        }
        const nuevas = parseInt(selPlan.selectedOptions[0].dataset.clases, 10) || 0;
        inputClases.value = Math.min(999, clasesIniciales + nuevas);
    });
})();
</script>

<?php endif; ?>

<?php endif; ?>