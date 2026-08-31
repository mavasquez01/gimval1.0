<main class="mobile-wrapper px-4">

    <div class="d-flex justify-content-center mb-4">
        <h5 class="text-white mb-0 text-center">
            Cambiar Contraseña
        </h5>
    </div>

    <div class="plan-card">
        <?php
        $alertaContrasena = $this->session->flashdata('alerta_contrasena');
        ?>

        <script>
            const alertaContrasenaBackend = <?= json_encode(
                $alertaContrasena,
                JSON_UNESCAPED_UNICODE
            ) ?>;
        </script>
        <form method="post" action="<?= site_url('alumna/guardarContrasena') ?>" id="formCambiarContrasena" novalidate>

            <div class="mb-3">
                <label class="form-label text-white">
                    Contraseña actual
                </label>

                <input type="password" name="contrasena_actual" id="contrasena_actual" class="form-control custom-input"
                    placeholder="Ingresa tu contraseña actual" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">
                    Nueva contraseña
                </label>

                <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control custom-input"
                    placeholder="Mínimo 8 caracteres" required>
            </div>

            <div class="mb-4">
                <label class="form-label text-white">
                    Confirmar nueva contraseña
                </label>

                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena"
                    class="form-control custom-input" placeholder="Repite tu nueva contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Actualizar contraseña
            </button>

            <a href="<?= site_url('alumna') ?>" class="btn btn-outline-primary w-100">
                Cancelar
            </a>

        </form>

    </div>

    <div class="schedule-card mt-4">

        <div class="d-flex align-items-center">

            <i class="bi bi-info-circle text-pink me-3"></i>

            <small class="text-secondary">
                Se recomienda utilizar una contraseña de al menos 8 caracteres,
                incluyendo letras y números.
            </small>

        </div>

    </div>

</main>