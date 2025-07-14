<?php
/**
 * Página para eliminar un artículo existente.
 *
 * Permite al usuario confirmar y eliminar un artículo específico de la base de datos.
 */

include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/helpers.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location:" . VIEWS_URL . "auth/login.php"); // Redirigir si no está autenticado
    exit();
}

// Verificar si el usuario tiene permiso para eliminar artículos (administrador o profesor)
if (!verificarPermiso(1) && !verificarPermiso(2)) {
    header("Location:" . BASE_URL . "index.php");
    exit();
}

// Verificar si se ha proporcionado un ID de artículo válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:" . VIEWS_URL . "users/view_profile.php"); // Redirigir a la página de perfil o a donde sea apropiado
    exit();
}

$article_id = $_GET['id'];
$user_id = $_SESSION["user_id"]; // Obtener el ID del usuario logueado

// Obtener la información del artículo para mostrar la confirmación
$sql_articulo = "SELECT title FROM articles WHERE id = ?";
$stmt_articulo = mysqli_prepare($conexion, $sql_articulo);
mysqli_stmt_bind_param($stmt_articulo, "i", $article_id);
mysqli_stmt_execute($stmt_articulo);
$result_articulo = mysqli_stmt_get_result($stmt_articulo);

if (!$result_articulo || mysqli_num_rows($result_articulo) == 0) {
    // Si no se encuentra el artículo, redirigir
    header("Location:" . VIEWS_URL . "users/view_profile.php");
    exit();
}

$article_data = mysqli_fetch_assoc($result_articulo);
$article_title = htmlspecialchars($article_data['title']);

mysqli_stmt_close($stmt_articulo);

// Procesar la confirmación de eliminación
if (isset($_POST['confirm_delete'])) {
    // Verificar el token CSRF (importante para seguridad)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Petición no válida.");
    }

    // Eliminar el artículo de la base de datos
    $sql_delete = "DELETE FROM articles WHERE id = ?";
    $stmt_delete = mysqli_prepare($conexion, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $article_id);

    if (mysqli_stmt_execute($stmt_delete)) {
        // Artículo eliminado con éxito, redirigir según el rol
        if (verificarPermiso(1)) { // Si es administrador
            header("Location:" . VIEWS_URL . "managers/gestor_articulos.php?delete_success=1");
            exit();
        } else { // Si es profesor (ya verificamos que tiene permiso)
            header("Location:" . VIEWS_URL . "users/view_profile.php?delete_success=1");
            exit();
        }
    } else {
        // Error al eliminar el artículo
        echo "Error al eliminar el artículo: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt_delete);
}

// Generar un token CSRF para seguridad
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));


?>

<?php
$tituloPagina = "Eliminar Artículo - " . htmlspecialchars($article_title);
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <div class="container mt-5 py-5 flex-grow-1">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 mt-5">
                <div class="card shadow rounded mt-5">
                    <div class="card-header bg-danger text-white text-center">
                        Confirmar Eliminación
                    </div>
                    <div class="card-body">
                        <p class="text-center mt-3 mb-1">¿Estás seguro de que deseas eliminar el siguiente artículo?</p>
                        <h1 class="card-title text-center py-2"><?php echo $article_title; ?></h1>
                        <form method="post" class="text-center py-2">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button type="submit" name="confirm_delete" class="btn btn-danger me-2">Sí, Eliminar</button>
                            <a href="<?php echo VIEWS_URL; ?>users/view_profile.php" class="btn btn-secondary">No, Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.add("show");
        });

        window.addEventListener("load", function () {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.remove("show");
        });
    </script>
</body>
</html>

<?php
mysqli_close($conexion);
?>