<?php

/**
 * Barra lateral reutilizable para diferentes páginas.
 *
 * Este archivo muestra un botón para crear un nuevo artículo, un formulario de búsqueda
 * y una lista de categorías. Se puede personalizar según el contexto (index, articles, etc.)
 * utilizando una variable `$context`.
 */

include_once(__DIR__ . "/../lib/constants.php");
include_once(__DIR__ . "/../lib/common.php");
include_once(__DIR__ . "/../lib/categories.php");

// Determina el contexto (por ejemplo, "index" o "articles").
$context = isset($context) ? $context : 'index'; // Valor predeterminado: 'index'

// Obtiene el nombre de las categorías y la ordena alfabeticamente
$categorias = ListadoCategorias($conexion);
?>

<div class="col-lg-4 mt-5">
    <?php if (verificarPermiso(1) || verificarPermiso(2)) : ?>
        <a href="<?php echo VIEWS_URL; ?>articles/create_article.php">
            <button class="btn btn-primary mt-2 mb-4">Crear Artículo</button>
        </a>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Escribe algo..." aria-label="Escribe algo..." aria-describedby="button-search" />
                    <button class="btn btn-primary" id="button-search" type="submit">Buscar!</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($categorias): ?>
        <?php if (count($categorias) > 0): ?>
            <div class="card mb-4">
                <div class="card-header">Categorías</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 bg-6">
                            <ul class="list-unstyled">
                                <?php foreach ($categorias as $category): ?>
                                    <li>
                                        <a href="<?php echo VIEWS_URL; ?>articles/articles_category.php?id=<?php echo urlencode($category); ?>" style="text-decoration: none;">
                                            <?php echo ($category); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
</div>
<?php else: ?>
    <p>No se encontraron categorías.</p>
<?php endif; ?>
<?php else: ?>
    <p>Error al obtener las categorías.</p>
<?php endif; ?>

<?php if ($context === 'articles'): ?>
    <div class="card mb-4">
        <div class="card-header">Información</div>
        <div class="card-body">
            Puedes colocar lo que quieras dentro de estos widgets laterales. Son fáciles de usar y cuentan con el componente de tarjeta Bootstrap 5.
        </div>
    </div>
<?php endif; ?>
</div>