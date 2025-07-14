<?php
/**
 * Script para agregar un nuevo libro a la base de datos.
 * Recibe los datos del formulario y utiliza la clase Books para realizar la inserción.
 */

include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../class/books.php");

$books = new Books($id, $title, $image, $file, $sumary, $date, $author, $category);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Procesamiento de la subida de la portada
    $imagen = $books->procesarPortada($_FILES["portada"]);
    if ($imagen === false && isset($_FILES["portada"])) {
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?error=error_al_subir_portada");
        exit();
    }

    // Procesamiento de la subida del archivo del libro
    $archivo_libro = $books->procesarArchivoLibro($_FILES["libro_archivo"]);
    if ($archivo_libro === false && isset($_FILES["libro_archivo"])) {
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?error=error_al_subir_libro");
        exit();
    }

    $title = $_POST["titulo"] ?? '';
    $image = $_POST["portada"] ?? '';
    $file = $_POST["libro_archivo"] ?? '';
    $sumary = $_POST["resumen"] ?? '';
    $author = $_POST["autor"] ?? '';
    $category = $_POST["categoria"] ?? '';

    $resultado = $books->agregarLibro(
        $conexion,
        $title,
        $image,
        $file,
        $sumary,
        $author,
        $category,
        
    );

    if ($resultado) {
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?mensaje=libro_agregado");
        exit();
    } else {
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?error=error_al_agregar");
        exit();
    }
    } else {
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php");
        exit();
    }
    if(isset($conexion)) {
        $conexion->close();
    }
?>
