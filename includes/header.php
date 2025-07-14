<?php
include_once(__DIR__ . "/../lib/constants.php");
include_once(__DIR__ . "/../lib/common.php");
include_once(__DIR__ . "/../lib/helpers.php"); // Asegúrate de incluir el archivo de helpers
include_once(__DIR__ . "/../lib/notifications.php"); // Incluimos el archivo de notificaciones

$notificaciones_no_leidas = [];

if (isset($_SESSION['user_id'])) {
    $user_id_logueado = $_SESSION['user_id'];
    $notificaciones_no_leidas = obtenerNotificacionesNoLeidas($conexion, $user_id_logueado);
}
?>
        <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#!">U.E.N. Dr. Idelfonso Vasquez</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>index.php">Inicio</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo VIEWS_URL; ?>categories/category_page.php">Categorías</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo VIEWS_URL; ?>books/librery_page.php">Biblioteca</a>
                        </li>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Notificaciones
                                    <?php if (!empty($notificaciones_no_leidas)): ?>
                                        <span class="badge bg-danger"><strong><?php echo count($notificaciones_no_leidas); ?></strong></span>
                                    <?php endif; ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end overflow-auto" aria-labelledby="notificationDropdown">
                                    <?php if (!empty($notificaciones_no_leidas)): ?>
                                        <?php foreach ($notificaciones_no_leidas as $notificacion): ?>
                                            <li><a class="dropdown-item" href="<?php echo VIEWS_URL; ?>articles/articles.php?id=<?php echo $notificacion['article_id']; ?>&notification_id=<?php echo $notificacion['id']; ?>">
                                                <?php echo htmlspecialchars($notificacion['message']); ?>
                                            </a></li>
                                        <?php endforeach; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-center" href="<?php echo VIEWS_URL; ?>notifications/history.php">Ver todas las notificaciones</a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item">No tienes notificaciones nuevas</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Mi Perfil
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo VIEWS_URL; ?>users/view_profile.php">Ver Perfil</a>
                                    </li>

                                    <?php if (verificarPermiso(1)): // Se asume que el valor 1 pertenece al rol de Administrador ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo VIEWS_URL; ?>managers/gestor_usuarios.php">Panel Administrativo</a>
                                    </li>
                                    <?php endif; ?>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo CONTROLLERS_URL; ?>logout.php">Cerrar Sesión</a>
                                    </li>
                                </ul>
                            </li>
                            <img width="30px" height="30px" class="rounded-circle mt-1"
                                 src="<?php echo UPLOADS_URL . 'profiles/' . ($_SESSION['imagen']); ?>"
                                 alt="Imagen de perfil" />
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="<?php echo VIEWS_URL; ?>auth/login.php">
                                <button class="btn btn-light ms-3">Iniciar Session</button>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        </header>