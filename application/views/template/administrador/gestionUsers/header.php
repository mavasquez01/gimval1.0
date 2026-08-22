<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/temaValk.css') ?>">
    <title>Document</title>
</head>

<body>
    <div class="container-fluid mt-2">

        <div class="position-relative d-flex align-items-center justify-content-center">

            <a href="panelAdmin.html" class="position-absolute start-0 ms-2 text-white text-decoration-none">
                <i class="bi bi-chevron-left fs-3"></i>
            </a>

            <ul class="nav nav-tabs justify-content-center" id="tabs-admin" role="tablist">

                <li class="nav-item me-3 ms-5" role="presentation">
                    <button class="nav-link active" id="alumnas-tab" data-bs-toggle="tab"
                        data-bs-target="#alumnas-tab-pane" type="button">
                        Alumnas
                    </button>
                </li>

                <li class="nav-item mx-3" role="presentation">
                    <button class="nav-link" id="profesores-tab" data-bs-toggle="tab"
                        data-bs-target="#profesores-tab-pane" type="button">
                        Profesores
                    </button>
                </li>

            </ul>

        </div>

    </div>