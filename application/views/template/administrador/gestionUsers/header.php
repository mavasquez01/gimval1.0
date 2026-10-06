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

            <!-- Los tabs los genera assets/js/gestionUsers.js -->
            <ul class="nav nav-tabs justify-content-center" id="tabs-admin" role="tablist"></ul>

        </div>

    </div>