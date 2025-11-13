<?php
namespace App\Core;

class Database {
    /** @var \mysqli|null */
    private static $conn = null;

    /**
     * Devuelve una conexión mysqli reutilizando la conexión global legacy si existe.
     * Lanza RuntimeException si no se puede crear/obtener la conexión.
     * @return \mysqli
     */
    public static function getConnection(): \mysqli {
        if (self::$conn !== null) {
            return self::$conn;
        }

        // 1) Si existe la conexión legacy global, reutilizarla
        if (isset($GLOBALS['conexion']) && $GLOBALS['conexion'] instanceof \mysqli) {
            self::$conn = $GLOBALS['conexion'];
            return self::$conn;
        }

        // 2) Intentar incluir el archivo de configuración y usar la variable $conexion
        $configPath = __DIR__ . '/../../config/conexion_mysqli.php';
        if (file_exists($configPath)) {
            include $configPath; // puede definir $conexion o las variables de conexión
            if (isset($conexion) && $conexion instanceof \mysqli) {
                self::$conn = $conexion;
                return self::$conn;
            }

            if (isset($servername, $username, $password, $dbname)) {
                $conn = @new \mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    throw new \RuntimeException('DB connection failed: ' . $conn->connect_error);
                }
                self::$conn = $conn;
                return self::$conn;
            }
        }

        throw new \RuntimeException('Database connection not available. Ensure legacy $conexion or config/conexion_mysqli.php exists.');
    }

    /** Permite inyectar una conexión manualmente (útil para tests). */
    public static function setConnection(\mysqli $connection): void {
        self::$conn = $connection;
    }
}
