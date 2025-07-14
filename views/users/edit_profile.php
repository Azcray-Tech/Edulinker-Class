<?php
/**
 * Página para que los usuarios autenticados editen su perfil.
 *
 * Permite a los usuarios modificar su nombre de usuario, foto de perfil y biografía.
 */

include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/user.php"); // Incluir el archivo con las funciones de lógica

verificarAutenticacion();
$user_id = $_SESSION["user_id"];

// Obtener los datos del usuario
$user_data = obtenerDatosUsuario($conexion, $user_id);

if (!$user_data) {
    echo "Error: Usuario no encontrado.";
    exit();
}

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = actualizarPerfilUsuario($conexion, $user_id, $_POST, $_FILES);

    if ($resultado["exito"]) {
        header("Location: view_profile.php");
        exit();
    } else {
        echo $resultado["error"];
    }
}
?>
<?php
$tituloPagina = "Editar Perfil de Usuario";
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <div class="container mt-5 mb-5 flex-grow-1 text-center">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white">
                        Editar Perfil de Usuario
                    </div>
                    <div class="card-body mt-4">
                        <form action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <img src="<?php echo UPLOADS_URL . "profiles/" . htmlspecialchars($user_data['imagen']); ?>" alt="Foto de Perfil" class="rounded-circle img-thumbnail" style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="mt-3 text-start fw-bold">
                                    <label for="imagen" class="form-label">Cambiar Foto de Perfil</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                    <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG.</small>
                                </div>
                            </div>
                            <div class="mb-3 mt-3 text-start fw-bold">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                            </div>
                            <div class="mb-3 text-start fw-bold">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                                <small class="form-text text-muted">El correo electrónico no se puede editar.</small>
                            </div>
                            <div class="mb-3 text-start fw-bold">
                                <label for="biografia" class="form-label">Biografía</label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="3" required><?php echo htmlspecialchars($user_data['biografia']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4 mb-3">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include(__DIR__ . "/../../includes/footer.php"); ?>
</body>
</html>