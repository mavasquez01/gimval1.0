<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/temaValk.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <title>Editor de Rutina - Profesor</title>
    </head>

    <body class="">

        <nav class="navbar bg-dark bg-body-tertiary sticky-top" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand ms-2 d-flex align-items-center text-white text-decoration-none" href="panelProfesor_integrado.html">
                    <i class="bi bi-chevron-left fs-3"></i>
                </a>
            </div>
        </nav>

        <main class="mobile-wrapper mt-3 pb-5 px-4">
            <div class="text-center mb-4">
                <h4 class="text-white mb-0">Crear Rutina</h4>
                <p class="text-pink fw-semibold mb-0">Añade los ejercicios que realizará la alumna</p>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                <p class="text-white mb-0 fw-bold fs-5">Ejercicios</p>
            </div>
            <div id="contenedorEjercicios">
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
                            <input type="text"
                                   class="form-control custom-input text-white fw-semibold"
                                   placeholder="Ej: Sentadilla con barra">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-white small mb-1">
                                    Series x Reps
                                </label>
                                <input type="text"
                                       class="form-control custom-input text-center"
                                       placeholder="Ej: 4 x 12">
                            </div>

                            <div class="col-6">
                                <label class="form-label text-white small mb-1">
                                    Peso Sugerido (kg)
                                </label>
                                <input type="number"
                                       class="form-control custom-input text-center"
                                       placeholder="Ej: 60">
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
                            <input type="text"
                                   class="form-control custom-input text-white fw-semibold"
                                   placeholder="Ej: Peso Muerto">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-white small mb-1">
                                    Series x Reps
                                </label>
                                <input type="text"
                                       class="form-control custom-input text-center"
                                       placeholder="Ej: 4 x 10">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-white small mb-1">
                                    Peso Sugerido (kg)
                                </label>
                                <input type="number"
                                       class="form-control custom-input text-center"
                                       placeholder="Ej: 70">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button"
                    class="btn btn-outline-pink w-100 py-2 border-dashed mb-4 rounded-3">
                <i class="bi bi-plus-circle me-2"></i>
                Añadir otro ejercicio
            </button>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                GUARDAR RUTINA
            </button>

        </main>

        <script src="../js/bootstrap.bundle.min.js"></script>
    </body>
</html>