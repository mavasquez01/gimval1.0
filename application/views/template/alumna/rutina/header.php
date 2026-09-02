<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap_icons/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/temaValk.css') ?>">
    <title>Cambiar Contraseña</title>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-dark bg-body-tertiary" data-bs-theme="dark">

        <div class="container-fluid">

            <a href="<?=base_url('/alumna?clases')?>" class="text-white text-decoration-none ms-2">

                <i class="bi bi-chevron-left fs-4"></i>

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('/index')?>">
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="<?=base_url('/alumna?clases')?>">
                            Mis clases
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url("/alumna?agendar")?>">
                            Agendar
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url("/alumna?perfil")?>">
                            Perfil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('/alumna/cerrarSesion')?>">
                            Cerrar Sesión
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
    <script>const BASE_URL = "<?= base_url() ?>";</script>