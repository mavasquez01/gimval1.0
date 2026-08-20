<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valkiria Center</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/temaValk.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/temaValk.css') ?>">
</head>

<body class="landing-page">

    <nav class="navbar navbar-expand-lg bg-dark sticky-top" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand p-0" href="index.html">
                <img src="static/logoval.png" class="landing-logo" alt="Logo Valkiria Center">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarPrincipal" aria-controls="navbarPrincipal" aria-expanded="false"
                    aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPrincipal">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link active" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#planes">Planes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sobreNosotras">Sobre Nosotras</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nuestroEspacio">Nuestro Espacio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary btn-auto rounded-pill px-4" href="autenticacion/iniciarSesion.html">
                            Iniciar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>