<?php
session_start();
include '../include/db.php';

// Establecer la conexión a la base de datos
$db = new Database();
$conn = $db->conn;

function createAdmin($username, $email, $password) {
    global $conn;

    // Verificar si ya existe un administrador
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'admin'");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si ya hay un administrador, no creamos otro
        echo "Ya existe un administrador en el sistema.";
    } else {
        // Si no hay un administrador, creamos uno
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Administrador creado exitosamente.";
        } else {
            echo "Error al crear el administrador: " . $conn->error;
        }
        $stmt->close();
    }
}

// Llamar a la función para crear un administrador
createAdmin('admin', 'admin@example.com', 'Admin@2024#Secure');

// Cerrar la conexión a la base de datos
$db->close();
?>
