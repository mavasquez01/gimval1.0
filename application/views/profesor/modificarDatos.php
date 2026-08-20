<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../css/temaValk.css" rel="stylesheet" type="text/css">
        <link href="../css/bootstrap-icons.css" rel="stylesheet" type="text/css">
        <title>Modificar Datos</title>
    </head>

    <body>

        <nav class="navbar navbar-expand-lg bg-dark bg-body-tertiary" data-bs-theme="dark">

            <div class="container-fluid">

                <a class="navbar-brand ms-2" href="panelProfesor_integrado.html">

                     <i class="bi bi-chevron-left fs-3"></i>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent">

                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="panelProfesor_integrado.html">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="panelProfesor_integrado.html">Clases</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="panelProfesor_integrado.html">Rutinas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="panelProfesor_integrado.html">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../autenticacion/iniciarSesion.html">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="mobile-wrapper px-4">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-white mb-2">
                    Modificar datos
                </h3>

                <p class="text-pink mb-0">
                    Actualiza tu información personal
                </p>
            </div>

            <div class="plan-card">

                <form>

                    <div class="mb-3">
                        <label class="form-label text-white" for="nombres">
                            Nombres
                        </label>

                        <input type="text"
                               id="nombres"
                               class="form-control custom-input"
                               value="Camila">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="apellidos">
                            Apellidos
                        </label>

                        <input type="text"
                               id="apellidos"
                               class="form-control custom-input"
                               value="González">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="correo">
                            Correo electrónico
                        </label>

                        <input type="email"
                               id="correo"
                               class="form-control custom-input"
                               value="camila@gmail.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white" for="telefono">
                            Teléfono
                        </label>

                        <input type="tel"
                               id="telefono"
                               class="form-control custom-input"
                               value="+56 9 1234 5678">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white" for="fechaNacimiento">
                            Fecha de nacimiento
                        </label>

                        <input type="date"
                               id="fechaNacimiento"
                               class="form-control custom-input"
                               value="1998-05-12">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Guardar cambios
                    </button>

                    <a href="panelAlumna.html" class="btn btn-outline-primary w-100">
                        Cancelar
                    </a>

                </form>

            </div>

        </main>

        <script src="../js/bootstrap.bundle.min.js"></script>

    </body>

</html>