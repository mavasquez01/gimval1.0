<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/temaValk.css') ?>">
    <title>Panel Admin</title>
</head>

<body>
    <!-- HEADER ADMIN CARGADO -->
    <nav class="navbar navbar-expand-lg bg-dark bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.html">
                <img src="<?= base_url('/assets/images/logoval.png')?>" class="landing-logo" alt="Logo Valkiria Center">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?=base_url('index.php/administrador')?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('index.php/administrador/horarios')?>">Horarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('index.php/administrador/gestionUsers')?>">Alumnas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('index.php/administrador/gestionUsers')?>">Profesores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('index.php')?>">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>