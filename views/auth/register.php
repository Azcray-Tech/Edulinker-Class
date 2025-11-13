<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
?>

<?php
$tituloPagina = "Registrate";
include_once(__DIR__ . "/../../includes/head.php");
?>

<style>
    .bg {
        background-image: url('<?php echo UPLOADS_URL; ?>register.png');
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
            <div class="col">
                <div class="text-end">
                    <img class="img-fluid rounded" src="" alt="">
                </div>
                <h2 class="fw-bold text-center py-5 mt-4">¡Regístrate y empecemos!</h2>
                <form action="<?php echo BASE_URL; ?>controllers/users/process_register.php" method="post">
                    <div class="col-md-10 mx-auto mb-4">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="col-md-10 mx-auto mb-4">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-md-10 mx-auto mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-10 mx-auto d-grid mb-5">
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </div>
                    <div class="col-md-10 mx-auto text-center my-5">
                        <span>¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>views/auth/login.php" style="text-decoration: none;">Iniciar Sesión</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
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