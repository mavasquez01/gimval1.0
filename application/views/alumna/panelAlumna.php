<main class="mobile-wrapper">

    <div class="tab-content" id="dashboardTabsContent">

        <!-- Inicio -->
        <section class="tab-pane fade show active px-4" id="inicio" role="tabpanel" aria-labelledby="inicio-tab">

            <!-- Welcome -->
            <section class="mb-4">
                <h4 class="text-white mb-1">
                    ¡Hola,
                    <?= html_escape($this->session->userdata('nombre')) ?>!
                </h4>

                <p class="text-secondary mb-0">
                    ¿Lista para entrenar hoy?
                </p>
            </section>

            <?php if ($al_01): ?>
                 <!-- Próxima clase -->
            <section class="plan-card">

            

                <p class="text-secondary mb-2">
                    Próxima Clase
                </p>

                <div class="d-flex justify-content-between align-items-start gap-3">

                    <div>
                        <h2 class="text-white mb-1">
                            <?php
                             if ($al_01->fecha == date('Y-m-d')) {
                                echo "Hoy ", $al_01->hora_inicio;
                            } else {
                                $fecha = new DateTime($al_01->fecha);
                                echo $fecha->format('d-m-Y'), " ", $al_01->hora_inicio;
                            }
                            ?>
                        </h2>

                        <h5 class="text-white mb-0">
                            Grupal
                        </h5>
                    </div>

                    <i class="bi bi-clock-history text-pink fs-1"></i>

                </div>

                <hr class="class-divider">

                <div class="d-flex justify-content-between align-items-center">

                    <p class="text-secondary mb-0">
                        Profesora
                    </p>

                    <p class="text-white text-end mb-0">
                        <?= $al_01->nombre ?>
                    </p>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">

                    <p class="text-secondary mb-0">
                        Clases Restantes
                    </p>

                    <p class="text-white text-end mb-0">
                        <span class="text-pink fw-bold"><?= $al_03->clases_restantes ?></span> de <?= $al_03->total_clases ?>
                    </p>

                </div>

                <div class="text-center mt-4">
                    <a href="#" class="btn btn-outline-primary rounded-pill w-auto">
                        Ver horarios
                    </a>
                </div>

            </section>
            <?php else: ?>
                <p class="text-secondary text-center">No tienes clases próximas agendadas.</p>
            <?php endif; ?>

           

            <div class="mt-4">

                <h5 class="text-white text-center mb-4">
                    Próximas clases
                </h5>
                <?php
                foreach ($al_02 as $clase) {
                    $fecha = $clase->fecha;
                    $date = new DateTime($fecha);
                    $dia = $date->format('l');
                    $horadb = $clase->hora_inicio;
                    $hora = new DateTime($horadb);
                ?>
                

                        <div class="schedule-card mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-white mb-1">    
                                    <?php
                                        switch ($dia) {
                                        case 'Monday':
                                            echo "Lun ",$hora->format('H:i');
                                            break;
                                        case 'Tuesday':
                                            echo "Mar ",$hora->format('H:i');
                                            break;
                                        case 'Wednesday':
                                            echo "Mier ",$hora->format('H:i');
                                            break;
                                        case 'Thursday':
                                            echo "Jue ",$hora->format('H:i');
                                            break;
                                        case 'Friday':
                                            echo "Vie ",$hora->format('H:i');
                                            break;
                                        case 'Saturday':
                                            echo "Sab ",$hora->format('H:i');
                                            break;
                                        default:
                                            echo $clase->fecha," ",$hora->format('H:i');
                                            break;
                                    }
                                    ?>
                                    </h5>
                                </div>

                                <small class="text-pink">
                                    Prof. <?= $clase->nombre ?>
                                </small>
                            </div>
                        </div>

                <?php
                    }
                    ?>

            </div>

        </section>

        <!-- Agenda -->
<section class="tab-pane fade" id="agenda" role="tabpanel" aria-labelledby="agenda-tab">

    <div class="container-fluid px-4" style="max-width: 420px;">

        <div class="text-center mb-4">
            <p class="text-secondary mb-3" id="agenda-texto-semana">Cargando semana...</p>

            <ul class="nav nav-tabs border-0 justify-content-between" id="dias-tab" role="tablist">
                <!-- generado por JS -->
            </ul>
        </div>

        <div class="tab-content mt-4" id="dias-tab-content">
            <!-- generado por JS -->
        </div>

    </div>

</section>
        <!-- Clases -->
        <div class="tab-pane fade px-4" id="clases">

            <h4 class="text-white mb-4 text-center">
                Mis Clases
            </h4>

            <div class="tab-content">

                <!-- PAGINA 1 -->
                <div class="tab-pane fade show active" id="page1">

                    <!-- CARD 1 -->
                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">09:00</h4>

                                    <p class="text-white mb-2">
                                        Funcional - Camila
                                    </p>

                                    <small class="text-pink">
                                        12 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-full">
                                        COMPLETO
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <!-- CARD 2 -->
                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">11:00</h4>

                                    <p class="text-white mb-2">
                                        Spinning - Fernanda
                                    </p>

                                    <small class="text-pink">
                                        13 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-cupos">
                                        ACTIVA
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <!-- CARD 3 -->
                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">15:30</h4>

                                    <p class="text-white mb-2">
                                        GAP - Natalia
                                    </p>

                                    <small class="text-pink">
                                        14 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-cupos">
                                        ACTIVA
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <!-- CARD 4 -->
                    <a href="rutina.html">
                        <div class="schedule-card mb-4">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">19:00</h4>

                                    <p class="text-white mb-2">
                                        Cross Training - Camila
                                    </p>

                                    <small class="text-pink">
                                        15 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-full">
                                        COMPLETO
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                </div>

                <!-- PAGINA 2 -->
                <div class="tab-pane fade" id="page2">

                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">08:00</h4>

                                    <p class="text-white mb-2">
                                        Yoga - Daniela
                                    </p>

                                    <small class="text-pink">
                                        18 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-cupos">
                                        ACTIVA
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">12:00</h4>

                                    <p class="text-white mb-2">
                                        Funcional - Camila
                                    </p>

                                    <small class="text-pink">
                                        19 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-full">
                                        COMPLETO
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <a href="rutina.html">
                        <div class="schedule-card mb-3">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">17:00</h4>

                                    <p class="text-white mb-2">
                                        HIIT - Fernanda
                                    </p>

                                    <small class="text-pink">
                                        20 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-cupos">
                                        ACTIVA
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                    <a href="rutina.html">
                        <div class="schedule-card mb-4">

                            <div class="d-flex justify-content-between">

                                <div>
                                    <h4 class="fw-bold text-white mb-2">20:30</h4>

                                    <p class="text-white mb-2">
                                        Spinning - Natalia
                                    </p>

                                    <small class="text-pink">
                                        21 Mayo 2026
                                    </small>
                                </div>

                                <div class="d-flex flex-column justify-content-between align-items-end">

                                    <span class="badge-full">
                                        COMPLETO
                                    </span>

                                    <span class="text-pink fw-semibold me-2">
                                        VER RUTINA
                                    </span>

                                </div>

                            </div>

                        </div>
                    </a>

                </div>

            </div>

            <!-- PAGINATION -->
            <ul class="nav nav-tabs border-0 justify-content-center" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#page1" type="button">
                        1
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#page2" type="button">
                        2
                    </button>
                </li>

            </ul>

        </div>

        <div class="tab-pane fade" id="perfil">

            <div class="profile-card">

                <div class="d-flex align-items-center mb-4">

                    <img src="<?= base_url('/assets/images/alumna1.jpg') ?>" alt="Foto de perfil" class="profile-avatar me-3">

                    <div>

                        <?php if ($perfil): ?>

                            <h5 class="text-white mb-1">
                                <?= ($perfil->nombre) ?>
                                <?= ($perfil->apellido) ?>
                            </h5>

                            <p class="text-secondary mb-0">
                                <?= ($perfil->correo) ?>
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="plan-card mb-3">

                    <p class="text-white fw-semibold mb-1">
                        Mi plan
                    </p>

                    <p class="text-pink fw-bold mb-1">
                        Plan Grupal Fit
                    </p>

                    <small class="text-secondary d-block">
                        Vence el 30/06/2026
                    </small>

                    <small class="text-white d-block mt-2">
                        5 de 24 clases restantes
                    </small>

                </div>

                <div class="schedule-card mb-2">
                    <a href="<?= site_url('alumna/modificarDatos') ?>" class="d-flex align-items-center text-white">
                        <i class="bi bi-person me-3 text-pink"></i>
                        Modificar mis datos
                    </a>
                </div>

                <div class="schedule-card mb-2">
                    <a href="cambiarContraseña.html" class="d-flex align-items-center text-white">
                        <i class="bi bi-key me-3 text-pink"></i>
                        Cambiar contraseña
                    </a>
                </div>
                <div class="schedule-card mb-2">
                    <a href="convenios.html" class="d-flex align-items-center text-white">
                        <i class="bi bi-person me-3 text-pink"></i>
                        Ver Convenios
                    </a>
                </div>

                <div class="schedule-card">
                    <a href="<?= site_url('alumna/cerrarSesion') ?>" class="d-flex align-items-center text-pink">
                        <i class="bi bi-box-arrow-right me-3"></i>
                        Cerrar sesión
                    </a>
                </div>

            </div>

        </div>

    </div>

</main>