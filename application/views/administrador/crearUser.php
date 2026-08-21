<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/temaValk.css" rel="stylesheet" type="text/css" />
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="#"><img
                    src="../static/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Horarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Alumnas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profesores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid" style="max-width: 420px;">
        <div class="row text-center mb-4">
            <div class="col-12">
                <h2 class="text-white fw-bold">
                    Crear Usuario
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="">
                    <div class="mb-4">
                        <label for="rut"
                            class="form-label text-white">
                            RUT
                        </label>

                        <input type="text"
                            class="form-control custom-input"
                            name="rut"
                            placeholder="20.456.678-9">
                    </div>
                    <div class="mb-4">
                        <label for="rol"
                            class="form-label text-white">
                            Rol
                        </label>
                        <select class="form-select custom-input"
                            name="rol">

                            <option selected disabled>
                                Seleccionar rol
                            </option>

                            <option value="alumna">
                                Alumna
                            </option>

                            <option value="profesor">
                                Profesor
                            </option>

                            <option value="admin">
                                Administrador
                            </option>

                        </select>
                    </div>
                    <div class="d-grid mt-5">
                        <button type="submit"
                            class="btn btn-primary py-3 fw-bold rounded-4">
                            GUARDAR
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</main>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>