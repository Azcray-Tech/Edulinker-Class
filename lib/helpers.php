<?php
function mostrarArticulo($row, $seguirLeyendo) {
    $fechaPublicacion = date("d/m/Y", strtotime($row['date']));
    $categoriaLink = VIEWS_URL . "articles/articles_category.php?id=" . $row['category'];
    $articuloLink = VIEWS_URL . "articles/articles.php?id=" . $row['id'];
    $imagenSrc = UPLOADS_URL . "articles/cover/" . $row['image'];

    echo '<h1 class="fw-bolder mb-3">' . htmlspecialchars($row['title']) . '</h1>';
    echo '<figure class="mb-4"><img class="img-fluid rounded" width="1000px" src="' . htmlspecialchars($imagenSrc) . '" alt="Artículo" /></figure>';
    echo '<div class="text-muted fst-italic mb-2">Publicado el ' . htmlspecialchars($fechaPublicacion) . ' por ' . htmlspecialchars($row['username']) . '</div>';
    echo '<a class="badge bg-primary text-decoration-none link-light mb-2" href="' . htmlspecialchars($categoriaLink) . '">' . htmlspecialchars($row['category']) . '</a>';
    echo '<section class="mb-5">';
    echo '    <p style="text-align: justify;" class="fs-5 mb-4">' . strip_tags(cortarTexto($row['article'])) . '</p>';
    echo '    <a class="btn bg-primary text-decoration-none link-light mb-2" href="' . htmlspecialchars($articuloLink) . '">' . htmlspecialchars($seguirLeyendo) . '</a>';
    echo '</section>';
}

/**
 * Obtiene la lista de roles desde la base de datos.
 * @param mysqli $conexion La conexión a la base de datos.
 * @return array|null Un array con los roles o null en caso de error.
 */
function obtenerRoles($conexion) {
    $sql = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
    $result = mysqli_query($conexion, $sql);

    if ($result) {
        $roles = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $roles[] = $row;
        }
        mysqli_free_result($result); // Liberar el resultado
        return $roles;
    } else {
        // Manejar errores de consulta
        error_log("Error en la consulta obtenerRoles: " . mysqli_error($conexion));
        return null;
    }
}

/**
 * Obtiene el nombre del rol por su ID.
 * @param mysqli $conexion La conexión a la base de datos.
 * @param int $rol_id El ID del rol.
 * @return string|false El nombre del rol o false en caso de error.
 */
function obtenerRolPorId($conexion, $rol_id) {
    $sql = "SELECT nombre FROM roles WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $rol_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return $row['nombre'];
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}

/**
 * Verifica si el usuario tiene el permiso requerido.
 * @param int $rol_requerido_id El ID del rol requerido.
 * @return bool True si el usuario tiene el permiso, false en caso contrario.
 */
function verificarPermiso($rol_requerido_id) {
    if (!isset($_SESSION['rol_id'])) {
        // El usuario no ha iniciado sesión
        return false;
    }

    return $_SESSION['rol_id'] == $rol_requerido_id;
}

/**
 * Sanitiza el contenido HTML de un artículo, eliminando atributos no deseados como 'contenteditable'.
 * @param string $html El contenido HTML a sanitizar.
 * @return string El contenido HTML sanitizado.
 */
function sanitizeArticleContent($html) {
    // Usar DOMDocument para parsear y manipular el HTML de forma segura
    $dom = new DOMDocument();
    // Suprimir errores de HTML mal formado
    libxml_use_internal_errors(true);
    $dom->loadHTML('<div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//@contenteditable');

    foreach ($nodes as $node) {
        if ($node instanceof DOMAttr && $node->ownerElement) {
            $node->ownerElement->removeAttribute($node->name);
        }
    }

    // Obtener el HTML del body (o del div que envolvimos)
    $body = $dom->getElementsByTagName('div')->item(0);
    $sanitizedHtml = '';
    foreach ($body->childNodes as $node) {
        $sanitizedHtml .= $dom->saveHTML($node);
    }

    return $sanitizedHtml;
}
