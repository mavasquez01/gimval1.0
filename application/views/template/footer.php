

    <footer class="footer text-center">
        <div class="container">
            <h5 class="text-white">Nuestras redes sociales</h5>
            <div class="d-flex justify-content-center gap-3 gap-md-4 mt-3 flex-wrap">
                <a href="https://www.instagram.com/valkiriacenter?igsh=MWIwc3hpbHR4MnMweA==" class="text-decoration-none">
                    <img src="static/ig.png" class="social-icon" alt="Instagram">
                </a>
                <a href="#" class="text-decoration-none">
                    <img src="static/fb.png" class="social-icon" alt="Facebook">
                </a>
                <a href="#" class="text-decoration-none">
                    <img src="static/tk.png" class="social-icon" alt="TikTok">
                </a>
                <a href="https://wa.me/56962314079" class="text-decoration-none">
                    <img src="static/wsp.png" class="social-icon" alt="WhatsApp">
                </a>
            </div>
            <p class="mb-0 mt-4 text-secondary">
                &copy; 2026 Valkiria Center. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <div class="modal fade" id="modalPlanPersonalizado" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Condiciones Plan Personalizado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>• Todas las clases tienen una duración de 60 minutos.</p>
                    <p>• El pago está sujeto a modalidad 1:1, 1:2 o 1:3.</p>
                    <p>• Avisar inasistencia con 1 hora de anticipación.</p>
                    <p>• Las clases por inasistencia deben reagendarse dentro del mismo mes.</p>
                    <p class="mb-0">• Para congelar el plan por salud debe presentar licencia médica.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPlanDuo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Personalizado Dúo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>• Todas las clases tienen una duración de 60 minutos.</p>
                    <p>• Entrenamiento en dupla con acompañamiento profesional.</p>
                    <p>• Avisar inasistencia con 1 hora de anticipación.</p>
                    <p>• Las clases deben reagendarse dentro del mismo mes.</p>
                    <p class="mb-0">• Acceso a beneficios y descuentos profesionales.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPlanEstudiante" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Grupal Estudiante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>• Clases de 60 minutos y horario fijo.</p>
                    <p>• Modalidad grupal máximo 12 mujeres.</p>
                    <p>• Avisar inasistencia con 1 hora de anticipación.</p>
                    <p>• Reagendar dentro del mismo mes.</p>
                    <p class="mb-0">• Presentar certificado de alumna regular.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPlanGrupal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Grupal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>• Clases de 60 minutos y horario fijo.</p>
                    <p>• Modalidad grupal máximo 12 mujeres.</p>
                    <p>• Avisar inasistencia con 1 hora de anticipación.</p>
                    <p>• Reagendar dentro del mismo mes.</p>
                    <p class="mb-0">• Acceso a beneficios y descuentos profesionales.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const animatedElements = document.querySelectorAll(".reveal, .reveal-left, .reveal-right");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                }
            });
        }, {
            threshold: 0.15
        });

        animatedElements.forEach(element => {
            observer.observe(element);
        });
    </script>

     <script src="<?= base_url('/assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>