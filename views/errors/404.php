<?php
http_response_code(404);
include_once(__DIR__ . "/../../includes/head.php");
?>
<body class="d-flex flex-column min-vh-100">
    <?php include_once(__DIR__ . "/../../includes/header.php"); ?>
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="display-1">404</h1>
            <p class="lead">Página No Encontrada</p>
            <p>Lo sentimos, la página que busca no existe.</p>
            <a href="/" class="btn btn-primary">Volver a la página principal</a>
        </div>
    </div>
    <?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
</body>
</html>
