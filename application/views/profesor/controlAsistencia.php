<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../css/temaValk.css" rel="stylesheet" type="text/css" />
        <link href="../css/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <title>Control de Asistencia</title>
    </head>

    <body>

        
        <nav class="navbar bg-dark bg-body-tertiary sticky-top" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand ms-2 d-flex align-items-center text-white text-decoration-none" href="../profesor/panelProfesor_integrado.html">
                    <i class="bi bi-chevron-left fs-3"></i>
                    <span class="fs-5 fw-semibold ms-2">Asistencia</span>
                </a>
            </div>
        </nav>

        <main class="mobile-wrapper mt-3 pb-5 px-4">

            <!-- Tarjeta de Información de la Clase -->
            <div class="plan-card mb-2">
                
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">Funcional</h4>
                            <p class="text-pink mb-0 fw-semibold"><i class="bi bi-clock me-1"></i> 18:00 hrs</p>
                            <small class="text-secondary">Sala 2 • Lunes 12 Mayo</small>
                        </div>
                        <div class="text-end">
                            <h2 class="fw-bold text-white mb-0" id="contadorAsistencia">12<span class="fs-5 text-white-50">/15</span></h2>
                            <small class="text-secondary">Presentes</small>
                        </div>
                    </div>
                
            </div>

            <!-- Controles Rápidos -->
            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                <p class="text-secondary mb-0 fw-semibold">Lista de alumnas</p>
            </div>

            <!-- Lista de Alumnas (Switches) -->
            <div class="alumnas-list mb-4">

                <!-- Alumna 1 -->
                <div class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center">
                        <img src="../static/alumna1.jpg" alt="foto" class="rounded-circle me-3 border border-secondary" width="40" height="40">
                        <div>
                            <h6 class="text-white mb-0 fw-semibold">Maria Gonzalez</h6>
                            <small class="text-secondary">Plan Grupal</small>
                        </div>
                    </div>
                    <div class="form-check form-switch fs-4 mb-0">
                        <input class="form-check-input check-alumna" type="checkbox" checked>
                    </div>
                </div>

                <!-- Alumna 2 -->
                <div class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center">
                        <img src="../static/alumna1.jpg" alt="foto" class="rounded-circle me-3 border border-secondary" width="40" height="40">
                        <div>
                            <h6 class="text-white mb-0 fw-semibold">Carla Rojas</h6>
                            <small class="text-secondary">Plan Libre</small>
                        </div>
                    </div>
                    <div class="form-check form-switch fs-4 mb-0">
                        <input class="form-check-input check-alumna" type="checkbox">
                    </div>
                </div>

                <!-- Alumna 3 -->
                <div class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center">
                        <img src="../static/alumna1.jpg" alt="foto" class="rounded-circle me-3 border border-secondary" width="40" height="40">
                        <div>
                            <h6 class="text-white mb-0 fw-semibold">Valentina Pérez</h6>
                            <small class="text-secondary">Plan Grupal</small>
                        </div>
                    </div>
                    <div class="form-check form-switch fs-4 mb-0">
                        <input class="form-check-input check-alumna" type="checkbox" checked>
                    </div>
                </div>

                <!-- Alumna 4 -->
                <div class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center">
                        <img src="../static/alumna1.jpg" alt="foto" class="rounded-circle me-3 border border-secondary" width="40" height="40">
                        <div>
                            <h6 class="text-white mb-0 fw-semibold">Catalina Soto</h6>
                            <small class="text-secondary">Plan Estudiante</small>
                        </div>
                    </div>
                    <div class="form-check form-switch fs-4 mb-0">
                        <input class="form-check-input check-alumna" type="checkbox" checked>
                    </div>
                </div>

            </div>

            <div class="text-center mt-2 mb-4">
                <a href="clases.html" class="btn text-white w-100 fw-bold py-3 rounded-pill shadow" style="background-color: #e91e63;">
                    GUARDAR ASISTENCIA
                </a>
            </div>

        </main>

        <script src="../js/bootstrap.bundle.min.js"></script>

    </body>

</html>