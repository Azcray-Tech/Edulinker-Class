<?php
include_once(__DIR__ . "/../lib/helpers.php");
include_once(__DIR__ . "/../lib/common.php");
include_once(__DIR__ . "/../includes/head.php");

if (isset($resultados) && count($resultados) > 0) {
    echo "<h2>Resultados de la búsqueda:</h2>";
    echo "<hr>";
    foreach ($resultados as $row) {
        mostrarArticulo($row, SEGUIR_LEYENDO);
    }
} else {
    echo "<p>No se encontraron resultados para su búsqueda.</p>";
}

include_once(__DIR__ . "/../includes/footer.php");
?>
