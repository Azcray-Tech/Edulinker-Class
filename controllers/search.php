<?php
include_once(__DIR__ . "/../lib/common.php");
include_once(__DIR__ . "/../lib/articles.php");

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $resultados = buscarArticulos($conexion, $search);
} else {
    $resultados = [];
}

include_once(__DIR__ . "/../views/search_results.php");
?>
