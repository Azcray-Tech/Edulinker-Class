<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");

// Verificar si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}
?>

<?php
$tituloPagina = "Iniciar Sesión";
include_once(__DIR__ . "/../../includes/head.php");
?>

<style>
    .bg {
        background-image: url('<?php echo UPLOADS_URL; ?>login.png');
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
                    <img src="" alt="">
                </div>
                <h2 class="fw-bold text-center py-5 mt-5">¡Bienvenido!</h2>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_SESSION['login_error']); ?>
                        <?php unset($_SESSION['login_error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo CONTROLLERS_URL ?>users/process_login.php " method="post">
                    <div class="mb-4 col-md-10 mx-auto">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-4 col-md-10 mx-auto">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="d-grid mb-5 col-md-10 mx-auto">
                        <button type="submit" class="btn btn-primary">Iniciar Sessión</button>
                    </div>
                    <div class="my-5 text-center">
                        <span>¿Aún no tienes cuenta? <a href="register.php" style="text-decoration: none;">Registrate</a></span><br>
                        <span><a href="recovery_password.php" style="text-decoration: none;">¿Olvidaste tu contraseña?</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include(__DIR__ . "/../../includes/footer.php"); ?>
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