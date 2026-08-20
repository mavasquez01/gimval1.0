
    <main id="inicio" class="landing-main">

        <section>
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4200" data-bs-pause="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="hero-slide">
                            <img src="<?= base_url('/assets/images/cr1.jpg') ?>" class="d-block w-100" alt="Entrenamiento Valkiria Center">
                            <div class="hero-overlay"></div>
                            <div class="hero-content">
                                <div class="container">
                                    <h1 class="hero-title text-white mb-3 d-1">
                                        Fuerza.<br>
                                        Disciplina.<br>
                                        Empoderamiento.
                                    </h1>
                                    <p class="lead hero-subtitle mb-4">
                                        Un espacio creado por y para mujeres.
                                    </p>
                                    <a href="#planes" class="btn btn-primary btn-lg rounded-pill px-4">
                                        Conoce nuestros planes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="hero-slide">
                            <img src="<?= base_url('/assets/images/cr2.jpg') ?>" class="d-block w-100" alt="Entrenamiento funcional">
                            <div class="hero-overlay"></div>
                            <div class="hero-content">
                                <div class="container">
                                    <h1 class="hero-title text-white mb-3">
                                        Transforma tu energía<br>
                                        en resultados.
                                    </h1>
                                    <p class="lead hero-subtitle mb-4">
                                        Entrena con propósito y descubre todo tu potencial.
                                    </p>
                                    <a href="#contacto" class="btn btn-primary btn-lg rounded-pill px-4">
                                        Quiero entrenar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="hero-slide">
                            <img src="<?= base_url('/assets/images/cr3.jpg') ?>" class="d-block w-100" alt="Comunidad Valkiria Center">
                            <div class="hero-overlay"></div>
                            <div class="hero-content">
                                <div class="container">
                                    <h1 class="hero-title text-white mb-3">
                                        Más que un gimnasio,<br>
                                        una experiencia.
                                    </h1>
                                    <p class="lead hero-subtitle mb-4">
                                        Bienestar, confianza y progreso en un solo lugar.
                                    </p>
                                    <a href="#sobreNosotras" class="btn btn-primary btn-lg rounded-pill px-4">
                                        Conócenos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>

        <section class="landing-section">
            <div class="container">
                <div class="row g-3 g-md-4">
                    <div class="col-6 col-lg-3 reveal delay-1">
                        <div class="feature-box">
                            <div class="feature-icon">💜</div>
                            <h6 class="fw-bold text-white">100% femenino</h6>
                            <p class="text-secondary mb-0">Un espacio seguro y motivador.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 reveal delay-2">
                        <div class="feature-box">
                            <div class="feature-icon">💪</div>
                            <h6 class="fw-bold text-white">Entrenamientos efectivos</h6>
                            <p class="text-secondary mb-0">Rutinas adaptadas a tus objetivos.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 reveal delay-3">
                        <div class="feature-box">
                            <div class="feature-icon">🤝</div>
                            <h6 class="fw-bold text-white">Comunidad Valkiria</h6>
                            <p class="text-secondary mb-0">Acompañamiento y motivación real.</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 reveal delay-1">
                        <div class="feature-box">
                            <div class="feature-icon">🏆</div>
                            <h6 class="fw-bold text-white">Profesionales</h6>
                            <p class="text-secondary mb-0">Entrenadoras comprometidas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-section pt-0" id="sobreNosotras">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center reveal">
                        <p class="section-kicker mb-2">Nuestra identidad</p>
                        <h2 class="display-5 fw-bold text-white">Sobre Nosotras</h2>
                        <div class="section-divider"></div>
                        <p class="lead text-secondary mt-4 mb-0">
                            Valkiria Center es un gimnasio 100% femenino ubicado en Linares.
                            Llevamos casi 3 años entregando un servicio cercano, profesional y motivador para mujeres
                            que buscan entrenar en un ambiente cómodo, exclusivo y con energía positiva.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-section" id="nuestroEspacio">
            <div class="container">
                <div class="text-center mb-5 reveal">
                    <p class="section-kicker mb-2">Conoce el lugar</p>
                    <h2 class="display-5 fw-bold text-white">Nuestro Espacio</h2>
                    <div class="section-divider"></div>
                </div>

                <div class="row g-3 g-md-4">
                    <div class="col-12 col-md-4 reveal-left">
                        <img src="<?= base_url('/assets/images/nuestroEspacio1.jpg') ?>" class="space-img" alt="Espacio de entrenamiento Valkiria">
                    </div>
                    <div class="col-12 col-md-4 reveal">
                        <img src="<?= base_url('/assets/images/nuestroEspacio2.jpg') ?>" class="space-img" alt="Sala de entrenamiento Valkiria">
                    </div>
                    <div class="col-12 col-md-4 reveal-right">
                        <img src="<?= base_url('/assets/images/nuestroEspacio3.jpg') ?>" class="space-img" alt="Equipamiento Valkiria">
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-section" id="planes">
            <div class="container">
                <div class="text-center mb-5 reveal">
                    <p class="section-kicker mb-2">Elige tu plan</p>
                    <h2 class="display-5 fw-bold text-white">Planes que se adaptan a ti</h2>
                    <div class="section-divider"></div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-lg-6 reveal-left">
                        <div class="soft-card">
                            <img src="<?= base_url('/assets/images/plan1.jpg') ?>" class="plan-img" alt="Plan personalizado">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-white">Plan Personalizado</h4>
                                <p class="plan-price">Desde $68.000</p>
                                <ul class="list-unstyled text-secondary mb-4">
                                    <li>✔ Inicial: 8 clases - $68.000</li>
                                    <li>✔ Elite: 12 clases - $96.000</li>
                                    <li>✔ Premium: 16 clases - $120.000</li>
                                </ul>
                                <button class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalPlanPersonalizado">
                                    Ver condiciones
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 reveal-right">
                        <div class="soft-card">
                            <img src="<?= base_url('/assets/images/plan3.jpg') ?>" class="plan-img" alt="Plan personalizado dúo">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-white">Plan Personalizado Dúo</h4>
                                <p class="plan-price">Desde 2 x $121.040</p>
                                <ul class="list-unstyled text-secondary mb-4">
                                    <li>✔ Prime: 8 clases - 2 x $121.040</li>
                                    <li>✔ Move: 12 clases - 2 x $170.880</li>
                                    <li>✔ Aura: 16 clases - 2 x $213.600</li>
                                </ul>
                                <button class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalPlanDuo">
                                    Ver condiciones
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 reveal-left">
                        <div class="soft-card">
                            <img src="<?= base_url('/assets/images/plan4.jpg') ?>" class="plan-img" alt="Plan grupal estudiante">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-white">Plan Grupal Estudiante</h4>
                                <p class="plan-price">Desde $35.000</p>
                                <ul class="list-unstyled text-secondary mb-4">
                                    <li>✔ Tribu: 8 clases - $35.000</li>
                                    <li>✔ Flow: 12 clases - $45.000</li>
                                    <li>✔ Power: 24 clases - $65.000</li>
                                </ul>
                                <button class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalPlanEstudiante">
                                    Ver condiciones
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 reveal-right">
                        <div class="soft-card">
                            <img src="<?= base_url('/assets/images/plan2.jpg') ?>" class="plan-img" alt="Plan grupal">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-white">Plan Grupal</h4>
                                <p class="plan-price">Desde $33.000</p>
                                <ul class="list-unstyled text-secondary mb-4">
                                    <li>✔ Active: 6 clases - $33.000</li>
                                    <li>✔ Vibra: 6 clases - $44.000</li>
                                    <li>✔ Level: 12 clases - $54.000</li>
                                    <li>✔ Fit: 16 clases - $75.000</li>
                                </ul>
                                <button class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalPlanGrupal">
                                    Ver condiciones
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-section pt-0">
            <div class="container">
                <div class="text-center mb-5 reveal">
                    <p class="section-kicker mb-2">Experiencias reales</p>
                    <h2 class="display-5 fw-bold text-white">Testimonios</h2>
                    <div class="section-divider"></div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-4 reveal delay-1">
                        <div class="testimonial-box text-center">
                            <img src="<?= base_url('/assets/images/user.webp') ?>" class="rounded-circle testimonial-avatar mb-3" alt="Camila R.">
                            <h5 class="fw-bold text-white">Camila R.</h5>
                            <div class="text-warning fs-5 mb-3">⭐⭐⭐⭐⭐</div>
                            <p class="text-secondary fst-italic mb-0">
                                "Desde que llegué a Valkiria me siento más fuerte y segura. El ambiente es increíble."
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 reveal delay-2">
                        <div class="testimonial-box text-center">
                            <img src="<?= base_url('/assets/images/user.webp') ?>" class="rounded-circle testimonial-avatar mb-3" alt="Valentina M.">
                            <h5 class="fw-bold text-white">Valentina M.</h5>
                            <div class="text-warning fs-5 mb-3">⭐⭐⭐⭐⭐</div>
                            <p class="text-secondary fst-italic mb-0">
                                "Las entrenadoras son profesionales y siempre están pendientes de ayudarte."
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 reveal delay-3">
                        <div class="testimonial-box text-center">
                            <img src="<?= base_url('/assets/images/user.webp') ?>" class="rounded-circle testimonial-avatar mb-3" alt="Fernanda P.">
                            <h5 class="fw-bold text-white">Fernanda P.</h5>
                            <div class="text-warning fs-5 mb-3">⭐⭐⭐⭐⭐</div>
                            <p class="text-secondary fst-italic mb-0">
                                "No es solo un gimnasio, es una comunidad donde todas avanzamos juntas."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-section" id="contacto">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 reveal">
                        <div class="soft-card p-4 p-md-5">
                            <div class="text-center mb-4">
                                <p class="section-kicker mb-2">Comienza hoy</p>
                                <h2 class="fw-bold text-white">Contáctanos</h2>
                                <p class="text-secondary mb-0">
                                    ¿Tienes dudas sobre nuestros planes o quieres comenzar a entrenar?
                                    Estamos aquí para ayudarte.
                                </p>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="<?= base_url('/assets/images/ubicacion.png') ?>" class="contact-icon" alt="Ubicación">
                                <div class="fw-semibold text-white">Manuel Rodríguez 1476, Linares</div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="<?= base_url('/assets/images/telefono.png') ?>" class="contact-icon" alt="Teléfono">
                                <div class="fw-semibold text-white">+56 9 7132 XXXX</div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="<?= base_url('/assets/images/correo.png') ?>" class="contact-icon" alt="Correo">
                                <div class="fw-semibold text-white">contacto@valkyriacenter.cl</div>
                            </div>

                            <a href="https://wa.me/56962314079" class="btn btn-primary btn-lg rounded-pill w-100">
                                Escribir por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>