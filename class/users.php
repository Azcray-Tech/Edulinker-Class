<?php
class Users
{

    private $id;
    private $username;
    private $image;
    private $bio;
    private $email;
    private $password;
    private $role;

    public function __construct($id, $username, $image, $bio, $email, $password, $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->image = $image;
        $this->bio = $bio;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->role = $role;
    }

    // Getters and Setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    // Metodos de la clase Users

    /**
     * Registra un nuevo usuario en la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param string $username Nombre de usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @param int|null $rol_id ID del rol del usuario (opcional).
     * @param string $imagen_pre Nombre de la imagen predeterminada (opcional).
     * @return array Resultado de la operación (éxito o error).
     */
    public static function registrarUsuario($conexion, $username, $email, $password, $rol_id = null, $imagen_pre = "NoFoto.png")
    {
        // Verificar si el correo electrónico ya está registrado
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return ["exito" => false, "error" => "Ya existe un usuario registrado con este correo electrónico"];
        }

        // Encriptar la contraseña del usuario
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO users (username, email, password, rol_id, imagen) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssis", $username, $email, $passwordHash, $rol_id, $imagen_pre);
        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            mysqli_stmt_close($stmt);
            return ["exito" => true];
        } else {
            $error = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return ["exito" => false, "error" => "Error al registrar el usuario: $error"];
        }
    }

    /**
     * Inicia sesión de un usuario en la aplicación.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @return array Resultado de la operación (éxito o error).
     */
    public static function login($conexion, $email, $password)
    {
        $sql = "SELECT id, username, email, imagen, rol_id, password, state FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row['state'] == 'suspendido') {
                return ["exito" => false, "error" => "Esta cuenta ha sido suspendida. Por favor, contacte al administrador."];
            }

            if (password_verify($password, $row['password'])) {
                // Obtener el nombre del rol si existe
                $rol_nombre = null;
                if ($row['rol_id'] !== null) {
                    $sql_rol = "SELECT nombre FROM roles WHERE id = ?";
                    $stmt_rol = mysqli_prepare($conexion, $sql_rol);
                    mysqli_stmt_bind_param($stmt_rol, "i", $row['rol_id']);
                    mysqli_stmt_execute($stmt_rol);
                    $result_rol = mysqli_stmt_get_result($stmt_rol);
                    if (mysqli_num_rows($result_rol) == 1) {
                        $rol_row = mysqli_fetch_assoc($result_rol);
                        $rol_nombre = $rol_row['nombre'];
                    }
                    mysqli_stmt_close($stmt_rol);
                }
                return [
                    "exito" => true,
                    "user" => [
                        "id" => $row['id'],
                        "username" => $row['username'],
                        "email" => $row['email'],
                        "imagen" => $row['imagen'],
                        "rol_id" => $row['rol_id'],
                        "rol" => $rol_nombre
                    ]
                ];
            } else {
                return ["exito" => false, "error" => "Contraseña incorrecta"];
            }
        } else {
            return ["exito" => false, "error" => "Usuario no encontrado"];
        }
    }





    /**
     * Actualiza el perfil de un usuario, incluyendo su nombre, biografía e imagen de perfil.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param int $user_id ID del usuario.
     * @param array $data Datos del formulario (username, biografía).
     * @param array $files Datos del archivo subido (imagen).
     * @return array Resultado de la operación (éxito o error).
     */
    function actualizarPerfilUsuario($conexion, $user_id, $data, $files)
    {
        $username = $data["username"];
        $biografia = $data["biografia"];
        $imagen = null;

        // Manejo de la imagen de perfil
        if ($files["imagen"]["error"] == 0) {
            $target_dir = __DIR__ . "/../uploads/profiles/";
            $file_name = basename($files["imagen"]["name"]); // Solo el nombre del archivo
            $target_file = $target_dir . $file_name;

            // Verificar si la carpeta uploads/profiles existe
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Crear la carpeta si no existe
            }

            // Mover el archivo subido
            if (move_uploaded_file($files["imagen"]["tmp_name"], $target_file)) {
                $imagen = $file_name; // Guardar solo el nombre del archivo
            } else {
                return ["exito" => false, "error" => "Error al subir la imagen."];
            }
        }

        // Actualizar los datos en la base de datos
        $sql = "UPDATE users SET username = ?, biografia = ?" . ($imagen ? ", imagen = ?" : "") . " WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            if ($imagen) {
                mysqli_stmt_bind_param($stmt, "sssi", $username, $biografia, $imagen, $user_id);
            } else {
                mysqli_stmt_bind_param($stmt, "ssi", $username, $biografia, $user_id);
            }

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return ["exito" => true];
            }

            mysqli_stmt_close($stmt);
        }

        return ["exito" => false, "error" => "Error al actualizar el perfil."];
    }

    /**
     * Obtiene la lista de usuarios con un filtro opcional de búsqueda.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param string $search Texto de búsqueda opcional.
     * @return array Lista de usuarios.
     */
    function obtenerListaUsuarios($conexion, $search = '')
    {
        $usuarios = [];
        $sql = "SELECT u.id, u.username, u.email, u.rol_id, r.nombre AS rol_nombre, u.state
                FROM users u
                LEFT JOIN roles r ON u.rol_id = r.id";

        // Agregar filtro de búsqueda si se proporciona
        if (!empty($search)) {
            $search = $conexion->real_escape_string($search);
            $sql .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
        }

        $sql .= " ORDER BY id DESC";

        // Ejecutar la consulta
        $result = $conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }

    /**
     * Función para obtener la información del usuario de la sesión actual.
     *
     * Esta función asume que la información del usuario se almacena en la variable de sesión $_SESSION.
     * La estructura específica de cómo se almacena esta información puede variar según tu sistema de autenticación.
     *
     * @return array|null Un array asociativo con la información del usuario si la sesión está activa y
     * la información del usuario está disponible, o null si no hay sesión activa
     * o la información del usuario no se encuentra.
     */
    function obtenerUsuarioSesion(): ?array
    {
        // Iniciar la sesión si aún no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si existe la clave de sesión que contiene la información del usuario
        // Ajusta 'usuario_sesion' a la clave que estés utilizando en tu sistema
        if (isset($_SESSION['usuario_sesion']) && is_array($_SESSION['usuario_sesion'])) {
            // Devolver el array con la información del usuario
            return $_SESSION['usuario_sesion'];
        } else {
            // Si no se encuentra la información del usuario en la sesión
            return null;
        }
    }
}
