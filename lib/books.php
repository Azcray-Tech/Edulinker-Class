<?php
include_once(__DIR__ . "/constants.php");
include_once(__DIR__ . "/common.php");

/**
 * Agrega un nuevo libro a la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param string $titulo Título del libro.
 * @param string $imagen URL de la imagen del libro.
 * @param string $categoria Categoría del libro.
 * @param string $resumen Resumen del libro.
 * @param string $fecha Fecha de publicación del libro.
 * @param string $autor Autor del libro.
 * @param string $archivo_libro Nombre del archivo del libro.
 * @return bool True si el libro se agregó correctamente, false en caso contrario.
 */
function agregarLibro($conexion, $titulo, $imagen, $categoria, $resumen, $autor, $archivo_libro) {
    $sql = "INSERT INTO books (title, image, category, summary, date, author, book)
            VALUES (?, ?, ?, ?, NOW(), ?, ?)";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("ssssss", $titulo, $imagen, $categoria, $resumen, $autor, $archivo_libro);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

/**
 * Actualiza los datos de un libro en la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $id ID del libro a actualizar.
 * @param string $titulo Nuevo título del libro.
 * @param string $categoria Nueva categoría del libro.
 * @param string $resumen Nuevo resumen del libro.
 * @param string $autor Nuevo autor del libro.
 * @param string $imagen Nueva URL de la imagen del libro (opcional).
 * @param bool $actualizar_imagen Indica si se debe actualizar la imagen (opcional).
 * @param string $archivo_libro Nuevo nombre del archivo del libro (opcional).
 * @param bool $actualizar_archivo Indica si se debe actualizar el archivo del libro (opcional).
 * @return bool True si el libro se actualizó correctamente, false en caso contrario.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta.
 */
function actualizarLibro($conexion, $id, $titulo, $categoria, $resumen, $autor, $imagen = '', $actualizar_imagen = false, $archivo_libro = '', $actualizar_archivo = false) {
    $sql = "UPDATE books SET title = ?, category = ?, summary = ?, author = ?";
    $tipos = "ssss";
    $params = [$titulo, $categoria, $resumen, $autor];

    if ($actualizar_imagen && !empty($imagen)) {
        $sql .= ", image = ?";
        $tipos .= "s";
        $params[] = $imagen;
    }

    if ($actualizar_archivo && !empty($archivo_libro)) {
        $sql .= ", book = ?";
        $tipos .= "s";
        $params[] = $archivo_libro;
    }

    $sql .= " WHERE id = ?";
    $tipos .= "i";
    $params[] = $id;

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param($tipos, ...$params);

    if ($stmt->execute()) {
        $stmt->close();
        return true; // El libro se actualizó correctamente
    } else {
        $stmt->close();
        return false; // Hubo un error al actualizar el libro
    }
}

/**
 * Elimina un libro de la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $id ID del libro a eliminar.
 * @return bool True si el libro se eliminó correctamente, false en caso contrario.
 */
function eliminarLibro($conexion, $id) {
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

?>
