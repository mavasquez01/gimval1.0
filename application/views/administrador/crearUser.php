<?php
$old = is_array($old ?? null) ? $old : [];
$rolActual = $old['rol'] ?? ($rolInicial ?? '');
?>
<main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid px-4" style="max-width: 420px;">

        <div class="row text-center mb-4">
            <div class="col-12">
                <h2 class="text-white fw-bold">
                    Crear Usuario
                </h2>
            </div>
        </div>

        <?php
        $usuarioOk = $this->session->flashdata('usuario_ok');
        $usuarioClave = $this->session->flashdata('usuario_clave');
        $usuarioError = $this->session->flashdata('usuario_error');
        ?>

        <?php if ($usuarioOk): ?>
            <div class="alert alert-success" role="status">
                <?= html_escape($usuarioOk) ?>

                <?php if ($usuarioClave): ?>
                    <hr class="my-2">
                    No se pudo enviar el correo. Entrega esta contraseña temporal
                    manualmente:
                    <strong class="user-select-all"><?= html_escape($usuarioClave) ?></strong>
                    <br>
                    <small>No se vuelve a mostrar.</small>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($usuarioError): ?>
            <div class="alert alert-danger" role="alert">
                <?= html_escape($usuarioError) ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">

                <?= form_open('administrador/guardarUser') ?>

                <input type="hidden" name="token_usuario"
                       value="<?= html_escape($tokenUsuario) ?>">

                <div class="mb-4">
                    <label for="correo" class="form-label text-white">
                        Correo electrónico
                    </label>

                    <input type="email" id="correo" name="correo"
                           class="form-control custom-input"
                           placeholder="alumna@correo.com"
                           maxlength="120"
                           value="<?= html_escape($old['correo'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="rol" class="form-label text-white">
                        Rol
                    </label>

                    <select id="rol" name="rol" class="form-select custom-input" required>
                        <option value="" <?= $rolActual === '' ? 'selected' : '' ?> disabled>
                            Seleccionar rol
                        </option>
                        <option value="alumna" <?= $rolActual === 'alumna' ? 'selected' : '' ?>>
                            Alumna
                        </option>
                        <option value="profesor" <?= $rolActual === 'profesor' ? 'selected' : '' ?>>
                            Profesor
                        </option>
                        <option value="admin" <?= $rolActual === 'admin' ? 'selected' : '' ?>>
                            Administrador
                        </option>
                    </select>
                </div>

                <p class="text-secondary small mb-0">
                    Se enviará una contraseña temporal a este correo.
                    El resto de los datos los completa la persona en su primer inicio de sesión.
                </p>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary py-3 fw-bold rounded-4">
                        GUARDAR
                    </button>
                </div>

                <?= form_close() ?>

            </div>
        </div>

    </div>

</main>