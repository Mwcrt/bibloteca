<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    public $conn;

    public function __construct() {
        // Cargar variables de entorno si están configuradas
        $this->servername = getenv('DB_HOST') ?: 'localhost';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->dbname = getenv('DB_NAME') ?: 'library';

        // Crear la conexión
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Verificar si hay errores en la conexión
        if ($this->conn->connect_error) {
            throw new Exception("Conexión fallida: " . $this->conn->connect_error);
        }

        // Configurar el charset a UTF-8 para evitar problemas con caracteres especiales
        $this->conn->set_charset("utf8");
    }

    // Método para ejecutar consultas genéricas
    public function query($sql) {
        $result = $this->conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta: " . $this->conn->error);
        }
        return $result;
    }

    // Método para preparar consultas
    public function prepare($sql) {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }
        return $stmt;
    }

    // Método para cerrar la conexión
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Uso en otro archivo
try {
    $db = new Database();
    $conn = $db->conn;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
