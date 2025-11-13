<?php
http_response_code(500);
include_once(__DIR__ . "/../../includes/head.php");
?>
<body class="d-flex flex-column min-vh-100">
    <?php include_once(__DIR__ . "/../../includes/header.php"); ?>
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="display-1">500</h1>
            <p class="lead">Error Interno del Servidor</p>
            <p>Lo sentimos, algo salió mal en nuestro servidor. Por favor, inténtelo de nuevo más tarde.</p>
            <a href="/" class="btn btn-primary">Volver a la página principal</a>
        </div>
    </div>
    <?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
</body>
</html>
