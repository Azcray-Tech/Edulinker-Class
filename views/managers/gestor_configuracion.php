<?php
include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/helpers.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

$tituloPagina = "Gestor de Configuración";
include(__DIR__ . "/../../includes/head.php");

?>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    <?php include(__DIR__ . "/../../includes/asideAdmin.php"); ?>

    <main class="col-lg-9">
        <article>
            <h1 class="fw-bolder mb-3">Configuración</h1>

            <form method="POST" action="<?php echo __DIR__ . '/../../guardar_configuracion.php'; ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Modo de Mantenimiento</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modoMantenimiento" name="modoMantenimiento" <?php if (('modo_mantenimiento')) echo 'checked'; ?>>
                        <label class="form-check-label" for="modoMantenimiento">Activar Modo de Mantenimiento</label>
                    </div>
                    <div id="mensajeMantenimientoOpciones" class="<?php if (!('modo_mantenimiento')) echo 'd-none'; ?>">
                        <label for="mensajeMantenimiento" class="form-label mt-2 fw-bold">Mensaje de Mantenimiento</label>
                        <textarea class="form-control" id="mensajeMantenimiento" name="mensajeMantenimiento" rows="3" placeholder="Mensaje que se mostrará a los usuarios"><?php echo ('En este momento la Aplicación se encuentra en Mantenimiento.'); ?></textarea>
                        <div class="form-text">Este es el mensaje que se le mostrará a los usuarios cuando el modo de mantenimiento esté activo.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Copia de Seguridad</label>
                    <p>Realiza una copia de seguridad completa de los datos almacenados en la aplicación.</p>
                    <a href="<?php echo __DIR__ . '/../../crear_backup.php'; ?>" class="btn btn-primary">Crear Copia de Seguridad</a>
                    <button type="submit" class="btn btn-success">Guardar Configuración</button>
                </div>
            </form>
        </article>
    </main>
    </div>
</div>
</div>

    <?php include(__DIR__ . "/../../includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadingOverlay = document.getElementById("loading-overlay");

            // Mostrar el overlay al cargar la página
            loadingOverlay.classList.add("show");

            // Ocultar el overlay después de que la página haya cargado completamente
            window.addEventListener("load", function () {
                loadingOverlay.classList.remove("show");
            });
        });

        const modoMantenimientoCheckbox = document.getElementById('modoMantenimiento');
        const mensajeMantenimientoOpciones = document.getElementById('mensajeMantenimientoOpciones');

        modoMantenimientoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                mensajeMantenimientoOpciones.classList.remove('d-none');
            } else {
                mensajeMantenimientoOpciones.classList.add('d-none');
            }
        });
    </script>
    <script src="<?php echo __DIR__ . '/../../js/scripts-admin.js'; ?>"></script>
</body>
</html>
