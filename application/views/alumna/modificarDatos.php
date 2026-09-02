<main class="mobile-wrapper px-4">

    <div class="text-center mb-4">
        <h3 class="fw-bold text-white mb-2">
            Modificar datos
        </h3>

        <p class="text-pink mb-0">
            Actualiza tu información personal
        </p>
    </div>

    <div class="plan-card">
        <?php
        $alertaDatos = $this->session->flashdata('alerta_datos');
        ?>

        <script>
            const alertaDatosBackend = <?= json_encode(
                $alertaDatos,
                JSON_UNESCAPED_UNICODE
            ) ?>;
        </script>
        <form method="post" action="<?= site_url('alumna/actualizarDatos') ?>" id="formModificarDatos" novalidate>

            <div class="mb-3">
                <label class="form-label text-white" for="nombres">
                    Nombres
                </label>

                <input type="text" id="nombre" name="nombre" class="form-control custom-input"
                    value="<?= html_escape($perfil->nombre) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-white" for="apellidos">
                    Apellidos
                </label>

                <input type="text" id="apellido" name="apellido" class="form-control custom-input"
                    value="<?= html_escape($perfil->apellido) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-white" for="correo">
                    Correo electrónico
                </label>

                <input type="email" id="correo" name="correo" class="form-control custom-input"
                    value="<?= html_escape($this->session->userdata('correo')) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-white" for="telefono">
                    Teléfono
                </label>

                <input type="tel" id="telefono" name="telefono" class="form-control custom-input"
                    value="<?= html_escape($perfil->telefono) ?>">
            </div>

            <div class="mb-4">
                <label class="form-label text-white" for="fechaNacimiento">
                    Fecha de nacimiento
                </label>

                <input type="date" id="fechaNacimiento" name="fecha_nacimiento" class="form-control custom-input"
                    value="<?= html_escape($perfil->fecha_nacimiento) ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Guardar cambios
            </button>

            <a href="<?= site_url('alumna') ?>" class="btn btn-outline-primary w-100">
                Cancelar
            </a>

        </form>

    </div>

</main>