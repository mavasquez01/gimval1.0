<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/temaValk.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap_icons/bootstrap-icons.css') ?>">
    <title>Gestionar Usuarios</title>
</head>

<body>
    <div class="container-fluid mt-2">

        <div class="position-relative d-flex align-items-center justify-content-center">

            <a href="<?= base_url('index.php/administrador') ?>"
                class="position-absolute start-0 ms-2 text-white text-decoration-none">
                <i class="bi bi-chevron-left fs-3"></i>
            </a>

            <ul class="nav nav-tabs justify-content-center" id="tabs-admin" role="tablist">

                <li class="nav-item me-3 ms-5" role="presentation">
                    <button class="nav-link <?= $tab === 'alumnas' ? 'active' : '' ?>" id="alumnas-tab"
                        data-bs-toggle="tab" data-bs-target="#alumnas-tab-pane" type="button" role="tab"
                        aria-controls="alumnas-tab-pane" aria-selected="<?= $tab === 'alumnas' ? 'true' : 'false' ?>">
                        Alumnas
                    </button>
                </li>

                <li class="nav-item mx-3" role="presentation">
                    <button class="nav-link <?= $tab === 'profesores' ? 'active' : '' ?>" id="profesores-tab"
                        data-bs-toggle="tab" data-bs-target="#profesores-tab-pane" type="button" role="tab"
                        aria-controls="profesores-tab-pane"
                        aria-selected="<?= $tab === 'profesores' ? 'true' : 'false' ?>">
                        Profesores
                    </button>
                </li>

            </ul>

        </div>

    </div>