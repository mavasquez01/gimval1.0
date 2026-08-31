<main class="mobile-wrapper px-4">

    <div class="d-flex justify-content-center align-items-center mb-4">
        <h5 class="text-white mb-0">
            Convenios
        </h5>
    </div>

    <div class="plan-card">

        <?php foreach ($convenios as $convenio): ?>

            <div class="convenio-item">

                <div class="convenio-logo">
                    <?= html_escape(substr($convenio->nombre_comercio, 0, 2)) ?>
                </div>

                <div class="convenio-info">

                    <h6 class="text-white mb-1">
                        <?= html_escape($convenio->nombre_comercio) ?>
                    </h6>

                    <small class="text-secondary d-block">
                        <?= html_escape($convenio->descripcion) ?>
                    </small>

                    <small class="text-secondary d-block">
                        Código:
                        <?= html_escape($convenio->codigo_promocional) ?>
                    </small>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>