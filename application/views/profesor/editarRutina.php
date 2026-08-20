<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/temaValk.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-icons.css" rel="stylesheet" type="text/css" />
    <title>Editar Rutina - Profesor</title>
</head>

<body>

    <nav class="navbar bg-dark sticky-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-2 d-flex align-items-center text-white text-decoration-none" href="panelProfesor_integrado.html">
                <i class="bi bi-chevron-left fs-3"></i>
            </a>
        </div>
    </nav>

    <main class="mobile-wrapper px-4">

        <div class="text-center mb-3">
            <h4 class="text-white mb-0">Editar Rutina</h4>
            <p class="text-pink fw-semibold mb-0">Edita los ejercicios de la rutina</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <p class="text-white mb-0 fw-bold fs-5">Ejercicios</p>
        </div>

        <div id="contenedorEjercicios">
            <div class="card schedule-card text-white mb-3 tarjeta-ejercicio">
                <div class="card-body p-2">

                    <div class="d-flex justify-content-end mb-1">
                        <button type="button" class="btn btn-sm text-danger btn-eliminar p-0">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-white small mb-1">
                            Nombre del ejercicio
                        </label>
                        <input type="text" class="form-control custom-input text-white fw-semibold"
                            value="Sentadilla con barra">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label text-white small mb-1">
                                Series x Reps
                            </label>
                            <input type="text" class="form-control custom-input text-center" value="4 x 12">
                        </div>

                        <div class="col-6">
                            <label class="form-label text-white small mb-1">
                                Peso Sugerido (kg)
                            </label>
                            <input type="number" class="form-control custom-input text-center" value="60">
                        </div>
                    </div>

                </div>
            </div>
            <div class="card schedule-card text-white mb-3 tarjeta-ejercicio">
                <div class="card-body p-3">

                    <div class="d-flex justify-content-end mb-1">
                        <button type="button" class="btn btn-sm text-danger btn-eliminar p-0">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white small mb-1">
                            Nombre del ejercicio
                        </label>
                        <input type="text" class="form-control custom-input text-white fw-semibold" value="Peso muerto">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label text-white small mb-1">
                                Series x Reps
                            </label>
                            <input type="text" class="form-control custom-input text-center" value="4 x 10">
                        </div>

                        <div class="col-6">
                            <label class="form-label text-white small mb-1">
                                Peso Sugerido (kg)
                            </label>
                            <input type="number" class="form-control custom-input text-center" value="70">
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <button type="button" class="btn btn-outline-pink w-100 py-2 border-dashed mb-4 rounded-3">
            <i class="bi bi-plus-circle me-2"></i>
            Añadir otro ejercicio
        </button>
    
            <button type="submit" class="btn btn-primary w-100">
                GUARDAR CAMBIOS
            </button>




    </main>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>