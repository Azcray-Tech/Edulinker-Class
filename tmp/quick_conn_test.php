<?php
// Pequeña prueba para verificar que lib/common.php define $conexion correctamente.
require __DIR__ . '/../lib/common.php';
if (isset($conexion) && ($conexion instanceof mysqli)) {
    echo "CONEXION_OK";
} else {
    echo "NO_CONEXION";
}
