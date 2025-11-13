<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
?>

<?php
$tituloPagina = "¿Olvidaste tu Contraseña?";
include_once(__DIR__ . "/../../includes/head.php");
?>

<style>
    .bg {
        background-image: url('<?php echo UPLOADS_URL; ?>recovery.jpg');
        background-size: cover;
        background-position: center center;
        height: 72vh;
    }
</style>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include_once(__DIR__ . "/../../includes/header.php"); ?>

    <div class="container w-75 mt-5 shadow mb-5 flex-grow-1">
        <div class="row align-items-stretch">
            <div class="col bg d-none d-lg-block">
                <img class="img-fluid rounded" src="" alt="">
            </div>
            <div class="col mt-5 py-5">
                <div class="text-end">
                    <img src="" alt="">
                </div>
                <h2 class="fw-bold text-center py-5 mt-5">¿Olvidaste tu contraseña?</h2>
                <div class="text-center col-md-10 mx-auto mb-4">
                    <p class="mb-3">Si no recuerdas tu <strong>contraseña</strong>, el instituto puede ayudarte a recuperarla. Solo debes dirigirte al plantel y <strong>solicitar su restauración</strong>.</p>
                    <p class="mb-auto">Volver a <a href="<?php echo VIEWS_URL; ?>auth/login.php" style="text-decoration: none;">Iniciar Sessión</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-auto">
        <?php include(__DIR__ . "/../../includes/footer.php"); ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.add("show");
        });

        window.addEventListener("load", function() {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.remove("show");
        });
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/scripts.js"></script>
</body>

</html>