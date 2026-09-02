<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-5 mt-md-4 pb-5">

                            <div>
                                <img src="<?= base_url('assets/images/logoval.png') ?>" width="150" alt="Logo Valkyria">

                                <h3 class="fw-bold mb-2 text-uppercase">
                                    Completa tus datos
                                </h3>

                                <h4 class="fw-bold mb-4 text-uppercase">
                                    para continuar
                                </h4>
                            </div>


                            <?php
                            $alertaPerfil = $this->session->flashdata('alerta_perfil');
                            ?>

                            <script>
                                const alertaPerfilBackend = <?= json_encode(
                                    $alertaPerfil,
                                    JSON_UNESCAPED_UNICODE
                                ) ?>;
                            </script>


                            <form id="formularioPerfil" method="post"
                                action="<?= site_url('autenticacion/guardarPerfil') ?>" novalidate>

                                <!-- RUT -->
                                <div class="form-outline form-white mb-4">

                                    <label class="form-label text-start d-block" for="rut">
                                        RUT
                                    </label>

                                    <input type="text" id="rut" name="rut" class="form-control form-control-lg"
                                        data-bs-theme="dark" placeholder="Ej: 12.345.678-9" autocomplete="off" required>

                                </div>


                                <!-- NOMBRE -->
                                <div class="form-outline form-white mb-4">

                                    <label class="form-label text-start d-block" for="nombre">
                                        Nombres
                                    </label>

                                    <input type="text" id="nombre" name="nombre" class="form-control form-control-lg"
                                        data-bs-theme="dark" placeholder="Ej: María" autocomplete="given-name" required>

                                </div>


                                <!-- APELLIDO -->
                                <div class="form-outline form-white mb-4">

                                    <label class="form-label text-start d-block" for="apellido">
                                        Apellidos
                                    </label>

                                    <input type="text" id="apellido" name="apellido"
                                        class="form-control form-control-lg" data-bs-theme="dark"
                                        placeholder="Ej: González" autocomplete="family-name" required>

                                </div>


                                <!-- FECHA NACIMIENTO -->
                                <div class="form-outline form-white mb-4">

                                    <label class="form-label text-start d-block" for="fecha_nacimiento">
                                        Fecha de nacimiento
                                    </label>

                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                        class="form-control form-control-lg" data-bs-theme="dark" required>

                                </div>


                                <!-- TELÉFONO -->
                                <div class="form-outline form-white mb-4">

                                    <label class="form-label text-start d-block" for="telefono">
                                        Teléfono
                                    </label>

                                    <input type="tel" id="telefono" name="telefono" class="form-control form-control-lg"
                                        data-bs-theme="dark" placeholder="Ej: +56912345678" autocomplete="tel" required>

                                </div>


                                <!-- BOTÓN -->
                                <button class="btn btn-primary btn-lg px-5" type="submit">
                                    GUARDAR Y CONTINUAR
                                </button>

                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>