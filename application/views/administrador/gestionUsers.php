<?php
$zonaHoraria = new DateTimeZone('America/Santiago');
$hoy = new DateTimeImmutable('today', $zonaHoraria);
?>
<main class="d-flex justify-content-center min-vh-100 px-4">
    <div class="tab-content w-100" id="myTabContent" style="max-width: 420px;">
        <div class="tab-pane fade <?= $tab === 'alumnas' ? 'show active' : '' ?>" id="alumnas-tab-pane" role="tabpanel"
            aria-labelledby="alumnas-tab" tabindex="0">
            <div class="row mt-3">
                <div class="col-12 px-4">

                    <!-- Search -->
                    <form action="<?= site_url('administrador/gestionUsers') ?>" method="get"
                        class="d-flex align-items-center gap-2 w-100" role="search">

                        <i class="bi bi-search text-white" aria-hidden="true"></i>

                        <input class="form-control flex-grow-1" type="search" name="buscar"
                            value="<?= html_escape($busqueda) ?>" placeholder="Buscar alumna"
                            aria-label="Buscar alumna">

                        <button class="btn btn-outline-primary px-4 w-auto" type="submit">
                            Buscar
                        </button>
                    </form>

                    <!-- Add button -->
                    <div class="mt-3 d-grid">
                        <a href="<?= base_url('index.php/administrador/crearUser') ?>" class="btn btn-outline-primary">
                            Agregar Alumna
                        </a>
                    </div>

                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <?php if (empty($alumnas)): ?>
                                <tr>
                                    <td class="text-center text-white py-4">
                                        No se encontraron alumnas.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alumnas as $alumna): ?>
                                    <tr>
                                        <td class="w-100">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-circle fs-1 text-white me-3"
                                                            aria-hidden="true"></i>

                                                        <div>
                                                            <p class="text-white mb-1">
                                                                <?= html_escape(trim(
                                                                    ($alumna->nombre ?? '') . ' ' .
                                                                    ($alumna->apellido ?? '')
                                                                )) ?>
                                                            </p>

                                                            <p class="text-white mb-0">
                                                                RUT <?= html_escape($alumna->rut) ?>
                                                            </p>

                                                            <?php
                                                            $mensajePlan = '';
                                                            $clasePlan = '';

                                                            if (!empty($alumna->fecha_termino_plan)) {
                                                                $fechaTexto = substr($alumna->fecha_termino_plan, 0, 10);

                                                                $vencimiento = DateTimeImmutable::createFromFormat(
                                                                    '!Y-m-d',
                                                                    $fechaTexto,
                                                                    $zonaHoraria
                                                                );

                                                                if ($vencimiento && $vencimiento->format('Y-m-d') === $fechaTexto) {
                                                                    $dias = (int) $hoy->diff($vencimiento)->format('%r%a');

                                                                    if ($dias < 0) {
                                                                        $mensajePlan = 'Plan vencido';
                                                                        $clasePlan = 'bg-danger';
                                                                    } elseif ($dias === 0) {
                                                                        $mensajePlan = 'El plan vence hoy';
                                                                        $clasePlan = 'bg-warning text-dark';
                                                                    } elseif ($dias <= 7) {
                                                                        $mensajePlan = 'Plan a punto de expirar: faltan '
                                                                            . $dias . ($dias === 1 ? ' día' : ' días');
                                                                        $clasePlan = 'bg-warning text-dark';
                                                                    } else {
                                                                        $mensajePlan = 'Plan activo';
                                                                        $clasePlan = 'bg-success';
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                            <?php if ($mensajePlan !== ''): ?>
                                                                <span class="badge <?= $clasePlan ?> mt-2">
                                                                    <?= html_escape($mensajePlan) ?>
                                                                </span>
                                                            <?php endif; ?>

                                                            <a href="<?= html_escape(
                                                                site_url('administrador/detalleUser')
                                                                . '?rut=' . rawurlencode($alumna->rut)
                                                            ) ?>" class="d-inline-block text-white mt-2">
                                                                Ver perfil
                                                                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center my-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm custom-pagination">

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    ‹
                                </a>
                            </li>

                            <li class="page-item active">
                                <a class="page-link" href="#">
                                    1
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    2
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    3
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    ›
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tab-pane fade <?= $tab === 'profesores' ? 'show active' : '' ?>" id="profesores-tab-pane"
            role="tabpanel" aria-labelledby="profesores-tab" tabindex="0">
            <div class="row mt-3">
                <div class="col-12 px-4">

                    <!-- Search -->
                    <form action="<?= site_url('administrador/gestionUsers') ?>" method="get"
                        class="d-flex align-items-center gap-2 w-100" role="search">

                        <input type="hidden" name="tab" value="profesores">

                        <i class="bi bi-search text-white" aria-hidden="true"></i>

                        <input class="form-control flex-grow-1" type="search" name="buscar_profesora"
                            value="<?= html_escape($busquedaProfesoras) ?>" placeholder="Buscar profesora"
                            aria-label="Buscar profesora por nombre, apellido o RUT">

                        <button class="btn btn-outline-primary px-4 w-auto" type="submit">
                            Buscar
                        </button>
                    </form>

                    <!-- Add button -->
                    <div class="mt-3 d-grid">
                        <a href="<?= base_url('index.php/administrador/crearUser') ?>" class="btn btn-outline-primary">
                            Agregar Profesora
                        </a>
                    </div>

                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <?php if (empty($profesoras)): ?>
                                <tr>
                                    <td class="text-center text-white py-4">
                                        No se encontraron profesoras.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($profesoras as $profesora): ?>
                                    <tr>
                                        <td class="w-100">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-person-circle fs-1 text-white me-3"
                                                            aria-hidden="true"></i>

                                                        <div>
                                                            <p class="text-white mb-1">
                                                                <?= html_escape(trim(
                                                                    $profesora->nombre . ' ' .
                                                                    $profesora->apellido
                                                                )) ?>
                                                            </p>

                                                            <p class="text-white mb-0">
                                                                RUT <?= html_escape($profesora->rut) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center my-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm custom-pagination">

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    ‹
                                </a>
                            </li>

                            <li class="page-item active">
                                <a class="page-link" href="#">
                                    1
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    2
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    3
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="#">
                                    ›
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>

</main>