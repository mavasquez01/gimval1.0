<main class="mobile-wrapper">

    <div class="tab-content" id="dashboardTabsContent">

        <!-- Inicio -->
        <section class="tab-pane fade show active px-4" id="inicio" role="tabpanel" aria-labelledby="inicio-tab">

            <!-- Welcome -->
            <section class="mb-4">
                <h4 class="text-white mb-1">
                    ¡Hola, <?= html_escape($perfil->nombre ?? 'Alumna') ?>!
                </h4>
                <p class="text-secondary mb-0">
                    ¿Lista para entrenar hoy?
                </p>
            </section>

            <?php if ($al_01): ?>
                <!-- Próxima clase -->
                <section class="plan-card">

                    <p class="text-secondary mb-2">Próxima Clase</p>

                    <div>
                        <h2 class="text-white mb-1">
                            <?php
                            $horan = new DateTime($al_01->hora_inicio);
                            if ($al_01->fecha === date('Y-m-d')) {
                                echo "Hoy " . $horan->format('H:i');
                            } else {
                                $fecha = new DateTime($al_01->fecha);
                                echo $fecha->format('d-m-Y') . " " . $horan->format('H:i');
                            }
                            ?>
                        </h2>
                    </div>

                    <hr class="class-divider">

                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-secondary mb-0">Profesora</p>
                        <p class="text-white text-end mb-0">
                            <?= html_escape($al_01->nombre ?? 'Por asignar') ?>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <p class="text-secondary mb-0">Clases Restantes</p>
                        <p class="text-white text-end mb-0">
                            <?php
                            // Obtención segura de propiedades sin importar si AL_03 devuelve objeto o array
                            $restantes = is_object($al_03) ? ($al_03->clases_restantes ?? 0) : ($al_03['clases_restantes'] ?? 0);
                            $total = is_object($al_03) ? ($al_03->total_clases ?? 0) : ($al_03['total_clases'] ?? 0);
                            ?>
                            <span class="text-pink fw-bold"><?= $restantes ?></span> de <?= $total ?>
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="#" id="btn-ver-horarios" class="btn btn-outline-primary rounded-pill w-auto">
                            Ver horarios
                        </a>
                    </div>

                </section>
            <?php else: ?>
                <p class="text-secondary text-center my-4">No tienes clases próximas agendadas.</p>
            <?php endif; ?>


            <!-- Lista de próximas clases -->
            <div class="mt-4">
                <h5 class="text-white text-center mb-4">Próximas clases</h5>

                <?php if (!empty($al_02) && is_array($al_02)): ?>
                    <?php
                    $dias = [
                        'Monday' => 'Lun',
                        'Tuesday' => 'Mar',
                        'Wednesday' => 'Mier',
                        'Thursday' => 'Jue',
                        'Friday' => 'Vie',
                        'Saturday' => 'Sab',
                        'Sunday' => 'Dom'
                    ];
                    ?>

                    <?php foreach ($al_02 as $clase): ?>
                        <?php
                        $date = new DateTime($clase->fecha);
                        $diaIngles = $date->format('l');
                        $diaEsp = $dias[$diaIngles] ?? $clase->fecha;
                        $hora = new DateTime($clase->hora_inicio);
                        ?>

                        <div class="schedule-card mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-white mb-1">
                                        <?= $diaEsp ?>         <?= $hora->format('H:i') ?>
                                    </h5>
                                </div>
                                <small class="text-pink">
                                    Prof. <?= html_escape($clase->nombre ?? 'Por asignar') ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary text-center small">No hay horarios disponibles registrados.</p>
                <?php endif; ?>
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

            <h4 class="text-white mb-3 text-center">
                Mis Clases
            </h4>

            <div id="listaClases">

                <div class="text-center text-white">
                    Cargando clases...
                </div>

            </div>

            <div id="paginacionClases" class="d-flex justify-content-center mt-3">
            </div>

        </div>

        <div class="tab-pane fade" id="perfil">

            <div class="profile-card">

                <div class="d-flex align-items-center mb-4">

                    <img src="<?= base_url('/assets/images/alumna1.jpg') ?>" alt="Foto de perfil"
                        class="profile-avatar m-auto">

                    <div>

                        <?php if ($perfil): ?>

                            <h5 class="text-white mb-1">
                                <?= html_escape($perfil->nombre) ?>
                                <?= html_escape($perfil->apellido) ?>
                            </h5>

                            <p class="text-secondary mb-0">
                                <?= html_escape($this->session->userdata('correo')) ?>
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="plan-card mb-3">

                    <?php if ($plan): ?>

                        <p class="text-pink fw-bold mb-1">
                            <?= html_escape($plan->nombre_plan) ?>
                        </p>

                        <small class="text-secondary d-block">
                            Vence el
                            <?= date('d/m/Y', strtotime($plan->fecha_termino)) ?>
                        </small>

                        <small class="text-white d-block mt-2">
                            <?= html_escape($plan->clases_restantes) ?>
                            de
                            <?= html_escape($plan->cantidad_clases) ?>
                            clases restantes
                        </small>

                    <?php else: ?>

                        <p class="text-secondary mb-0">
                            No tienes un plan activo.
                        </p>

                    <?php endif; ?>

                </div>

                <div class="schedule-card mb-2">
                    <a href="<?= site_url('alumna/modificarDatos') ?>" class="d-flex align-items-center text-white">
                        <i class="bi bi-person me-3 text-pink"></i>
                        Modificar mis datos
                    </a>
                </div>

                <div class="schedule-card mb-2">
                    <a href="<?= site_url('alumna/cambiarContrasena') ?>" class="d-flex align-items-center text-white">
                        <i class="bi bi-key me-3 text-pink"></i>
                        Cambiar contraseña
                    </a>
                </div>
                <div class="schedule-card mb-2">
                    <a href="<?= site_url('alumna/convenios') ?>" class="d-flex align-items-center text-white">
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