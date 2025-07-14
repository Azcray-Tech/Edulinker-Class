<?php
include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/user.php"); // Incluir el archivo user.php

// Verificar si se ha pasado un ID de usuario por la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id_to_view = $_GET['id'];

    // Utilizar la función obtenerDatosUsuario para obtener los datos
    $userData = obtenerDatosUsuario($conexion, $user_id_to_view);

    if ($userData) {
        $username = $userData["username"];
        $email = $userData["email"];
        // Verificar la ruta de la imagen mas tarde
        $imagen = UPLOADS_URL . "profiles/" . $userData["imagen"];
        $biografia = $userData["biografia"];
        

        $seguir_leyendo = SEGUIR_LEYENDO;
        ?>

        <?php
        $tituloPagina = "Perfil de Usuario";
        include(__DIR__ . "/../../includes/head.php");
        ?>

        <body class="d-flex flex-column min-vh-100">
            
            <div id="loading-overlay">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>

            <?php include(__DIR__ . "/../../includes/header.php"); ?>

            <div class="container mt-5 py-5 flex-grow-1 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow rounded">
                            <div class="card-header bg-primary text-white">
                                Perfil de Usuario
                            </div>
                            <div class="card-body mt-4">
                                <div class="text-center mb-4">
                                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Foto de Perfil" class="rounded-circle img-thumbnail" style="width: 180px; height: 180px; object-fit: cover;">
                                </div>
                                <div class="mb-3 mt-5">
                                    <strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($username); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($email); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Biografía:</strong> <?php echo htmlspecialchars($biografia); ?>
                                </div>
                                <a href="javascript:history.back()" class="btn btn-secondary btn-lg btn-block mt-4 mb-3">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include(__DIR__ . "/../../includes/footer.php"); ?>
            <script src="<?php echo BASE_URL; ?>/js/scripts.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const loadingOverlay = document.getElementById("loading-overlay");

                    // Mostrar el overlay al cargar la página
                    loadingOverlay.classList.add("show");

                    // Ocultar el overlay después de que la página haya cargado completamente
                    window.addEventListener("load", function () {
                        loadingOverlay.classList.remove("show");
                    });
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        // Mostrar mensaje de error si el usuario no se encuentra
        echo $tituloPagina = "Usuario no encontrado";
        include(__DIR__ . "/../../includes/head.php");
        echo "<body>
                <div class='container mt-5'>
                    <p class='alert alert-danger'>El usuario con ID $user_id_to_view no fue encontrado.</p>
                </div>
              </body>
              </html>";
    }

} else {
    // Mostrar mensaje de error si el ID de usuario no es válido
    echo $tituloPagina = "ID de usuario inválido";
    include(__DIR__ . "/../../includes/head.php");

    echo "<body>
            <div class='container mt-5'>
                <p class='alert alert-danger'>ID de usuario inválido.</p>
            </div>
          </body>
          </html>";
}
?>