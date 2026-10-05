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
                <?php else: ?>
                    <p class="text-white">
                        Sin plan registrado.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resultado de la extensión -->
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

    </div>

</main>