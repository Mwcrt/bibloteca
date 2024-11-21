<?php
session_start();
include '../include/db.php';

$db = new Database();
$conn = $db->conn;

class AdminCreator {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createAdmin($username, $email, $password) {
        if ($this->adminExists()) {
            echo "Ya existe un administrador en el sistema.";
            return;
        }

        $this->insertAdmin($username, $email, $password);
    }

    private function adminExists() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role = 'admin'");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    private function insertAdmin($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Administrador creado exitosamente.";
        } else {
            echo "Error al crear el administrador: " . $this->conn->error;
        }
        $stmt->close();
    }
}


$adminCreator = new AdminCreator($conn);
$adminCreator->createAdmin('admin', 'admin@gmail.com', 'Admin123#');

$db->close();
?>
