<?php
include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");

// Obtener el ID del libro desde la URL
$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si no hay ID, o no es válido, redirigir a la página principal de libros
if ($bookId <= 0) {
    header("Location: view_books.php"); // Ajusta la ruta si es necesario
    exit();
}

// Consulta para obtener la información del libro específico
$sqlLibro = "SELECT id, title, author, summary, image, category, book FROM books WHERE id = ?";
$stmtLibro = $conexion->prepare($sqlLibro);

if ($stmtLibro) {
    $stmtLibro->bind_param("i", $bookId);
    $stmtLibro->execute();
    $resultLibro = $stmtLibro->get_result();
    $libro = $resultLibro->fetch_assoc();
    $stmtLibro->close();

    if (!$libro) {
        // Si no se encuentra el libro, redirigir con un mensaje
        header("Location: view_books.php?error=libro_no_encontrado"); // Ajusta la ruta si es necesario
        exit();
    }
} else {
    echo "Error al preparar la consulta: " . $conexion->error;
    exit();
}

$tituloPagina = htmlspecialchars($libro['title']);
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">

    

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div class="container mt-5 py-3 flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow rounded">
                    <div class="row">
                        <div class="col-md-3">
                            <?php if (!empty($libro["image"])): ?>
                                <img src="<?php echo UPLOADS_URL . "books/cover/" . htmlspecialchars($libro["image"]); ?>" alt="<?php echo htmlspecialchars($libro["title"]); ?>" class="img-fluid rounded-start" style="object-fit: cover; height: 100%;">
                            <?php else: ?>
                                <div class="bg-light d-flex justify-content-center align-items-center rounded-start" style="height: 100%;">
                                    <span class="text-muted">Sin portada</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h2 class="card-title mt-3"><?php echo ($libro["title"]); ?></h2>
                                <p class="card-text mt-3 mb-0"><strong class="text-primary">Autor</strong>: <?php echo ($libro["author"] ?: 'Desconocido'); ?></p>
                                <p class="card-text mb-4"><strong class="text-primary">Categoría</strong>: <?php echo ($libro["category"]); ?></p>
                                <?php if (!empty($libro["summary"])): ?>
                                    <p class="card-text fw-semibold text-justify"><?php echo nl2br(($libro["summary"])); ?></p>
                                <?php endif; ?>
                                <div class="d-flex justify-content-end mt-4 py-3">
                                    <a href="<?php echo VIEWS_URL; ?>books/librery_page.php" class="btn btn-outline-secondary">Volver a la biblioteca</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($libro["book"])): ?>
            <div class="row mt-4 mb-5 py-5">
                <div class="col-lg-12">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <iframe src="<?php echo UPLOADS_URL . "books/files/" . ($libro["book"]); ?>" width="100%" height="1000px" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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