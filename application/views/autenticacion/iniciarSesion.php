
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-5 mt-md-4 pb-5">

                                    <div>
                                        <img src="<?=base_url('/assets/images/logoval.png')?>" width="150" alt="alt"/>
                                    </div>

                                    <form id="formularioInicioSesion" action="<?= site_url('autenticacion/iniciarSesion')?>" method="POST">

                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <label class="form-label text-start d-block" for="correo">Correo</label>
                                            <input type="email" id="correo" name="correo"
                                                   class="form-control form-control-lg"
                                                   data-bs-theme="dark"
                                                   placeholder="Ej: usuario@gmail.com">
                                        </div>
                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <label class="form-label text-start d-block" for="contrasena">Contraseña</label>
                                            <input type="password" id="contrasena" name="contrasena"
                                                   class="form-control form-control-lg"
                                                   data-bs-theme="dark"
                                                   placeholder="Ingresa tu contraseña">
                                        </div>
                                        
                                        <p class="small mb-5 pb-lg-2">
                                            <a class="text-pink" href="#!">¿Olvidaste tu contraseña?</a>
                                        </p> 
                                        
                                        <button class="btn btn-primary btn-lg px-5" type="submit">INGRESAR</button>
                                    </form>
                                </div>

                                <div>
                                    <p class="mb-0">¿No tienes cuenta? <a href="#!" class="text-white-50 fw-bold">Contactar al gimnasio</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
