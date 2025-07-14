<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/user.php");
include_once(__DIR__ . "/../../lib/helpers.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: " . VIEWS_URL . "auth/login.php");
    exit();
}
$user_id = $_SESSION["user_id"];

// Obtener los datos del usuario de la base de datos
$userData = obtenerDatosUsuario($conexion, $user_id);

if (!$userData) {
    echo "Usuario no encontrado.";
    exit();
}

$username = $userData["username"];
$email = $userData["email"];
$imagen = UPLOADS_URL . "profiles/" . $userData["imagen"];
$biografia = $userData["biografia"];
$rol_id = $userData["rol_id"];

$rolNombre = obtenerRolPorId($conexion, $rol_id);
if (!$rolNombre) {
    $rolNombre = "";
}

// Definir los roles que pueden ver la sección de artículos publicados
$rolesPermitidos = ['administrador', 'profesor']; // Asegúrate de que estos coincidan con los nombres en tu tabla de roles

// Configuración de la paginación
$articulosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $articulosPorPagina;

// Obtener los artículos creados por el usuario logueado con la imagen SOLO si el rol está permitido
$articulos_usuario = [];
$totalArticulos = 0; // Inicializar el total de artículos
if (in_array($rolNombre, $rolesPermitidos)) {
    // Obtener el total de artículos del usuario
    $sql_total_articulos = "SELECT COUNT(id) AS total FROM articles WHERE user = ?";
    $stmt_total_articulos = mysqli_prepare($conexion, $sql_total_articulos);
    if ($stmt_total_articulos) {
        mysqli_stmt_bind_param($stmt_total_articulos, "i", $user_id);
        mysqli_stmt_execute($stmt_total_articulos);
        $result_total_articulos = mysqli_stmt_get_result($stmt_total_articulos);
        $row_total_articulos = mysqli_fetch_assoc($result_total_articulos);
        $totalArticulos = $row_total_articulos['total'];
        mysqli_stmt_close($stmt_total_articulos);
    } else {
        echo "Error al obtener el total de artículos del usuario.";
    }

    $sql_articulos = "SELECT id, title, article, image FROM articles WHERE user = ? LIMIT ?, ?";
    $stmt_articulos = mysqli_prepare($conexion, $sql_articulos);

    if ($stmt_articulos) {
        mysqli_stmt_bind_param($stmt_articulos, "iii", $user_id, $offset, $articulosPorPagina);
        mysqli_stmt_execute($stmt_articulos);
        $result_articulos = mysqli_stmt_get_result($stmt_articulos);

        while ($row_articulo = mysqli_fetch_assoc($result_articulos)) {
            $articulos_usuario[] = $row_articulo;
        }

        mysqli_stmt_close($stmt_articulos);
    } else {
        echo "Error al obtener los artículos del usuario.";
    }
}

$seguir_leyendo = SEGUIR_LEYENDO;
$totalPaginas = ceil($totalArticulos / $articulosPorPagina);
?>

<?php
$tituloPagina = "Perfil de Usuario";
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

<?php include(__DIR__ . "/../../includes/header.php"); ?>

    <div class="container mt-5 flex-grow-1 text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 py-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white">
                        Perfil de Usuario
                    </div>
                    <div class="card-body mt-4">
                        <div class="text-center mb-4">
                            <img src="<?php echo $imagen; ?>" alt="Foto de Perfil" class="rounded-circle img-thumbnail" style="width: 180px; height: 180px; object-fit: cover;">
                        </div>
                        <div class="mb-3 mt-5">
                            <strong class="me-1">Nombre:</strong> <?php echo htmlspecialchars($username); ?>
                            <?php if ($rolNombre): ?>
                                <span class="badge bg-primary ms-1"><?php echo htmlspecialchars($rolNombre); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <strong class="me-1">Correo Electrónico:</strong> <?php echo htmlspecialchars($email); ?>
                        </div>
                        <div class="mb-3">
                            <strong class="me-1">Biografía:</strong> <?php echo htmlspecialchars($biografia); ?>
                        </div>
                        <a href="<?php echo VIEWS_URL; ?>users/edit_profile.php" class="btn btn-primary btn-lg btn-block mt-4 mb-3">Editar Perfil</a>
                    </div>
                </div>

                <?php if (in_array($rolNombre, $rolesPermitidos)): ?>
                    <h2 class="mt-5 mb-3 text-center">Tus Artículos Publicados</h2>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3 mb-5">
                        <?php if (!empty($articulos_usuario)): ?>
                            <?php foreach ($articulos_usuario as $articulo): ?>
                                <div class="col">
                                    <div class="card h-100 shadow rounded d-flex flex-column">
                                        <?php if (!empty($articulo['image'])): ?>
                                            <img src="<?php echo UPLOADS_URL . "articles/cover/" . htmlspecialchars($articulo['image']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($articulo['title']); ?>">
                                        <?php endif; ?>
                                        <div class="card-body text-center d-flex flex-column justify-content-between">
                                            <h5 class="card-title mb-3"><?php echo htmlspecialchars($articulo['title']); ?></h5>
                                            <div class="d-flex justify-content-center gap-2 mt-auto">
                                                <a href="<?php echo VIEWS_URL; ?>articles/articles.php?id=<?php echo htmlspecialchars($articulo['id']); ?>" class="btn btn-primary">Ver</a>
                                                <a href="<?php echo VIEWS_URL; ?>articles/edit_article.php?id=<?php echo htmlspecialchars($articulo['id']); ?>" class="btn btn-warning">Editar</a>
                                                <a href="<?php echo VIEWS_URL; ?>articles/delete_article.php?id=<?php echo htmlspecialchars($articulo['id']); ?>" class="btn btn-danger">Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-lg-12 mt-4 mb-4">
                                <p class="text-center">No has publicado ningún artículo todavía.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($totalPaginas > 1): ?>
                        <nav class="mb-5 py-3" aria-label="Paginación de Artículos">
                            <ul class="pagination justify-content-center">
                                <?php if ($paginaActual > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>" aria-label="Anterior">
                                            Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($paginaActual < $totalPaginas): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>" aria-label="Siguiente">
                                            Siguiente
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/footer.php"); ?>
    <script src="<?php echo BASE_URL; ?>assets/js/scripts.js"></script>

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
    </script>
</body>
</html>