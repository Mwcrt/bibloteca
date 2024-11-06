<?php
// Archivo: login_register.php
include '../include/db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Crear instancia de Database
    $db = new Database();
    $conn = $db->conn;

    if ($action === "register") {
        // Registro de usuario
        $email = $_POST['email'];

        // Verificar si el usuario ya existe
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "El usuario o correo ya está registrado.";
        } else {
            // Encriptar la contraseña y registrar usuario
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                echo "Usuario registrado con éxito";
            } else {
                echo "Error al registrar usuario: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkStmt->close();
    } elseif ($action === "login") {
        // Inicio de sesión
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Guardar información en sesión
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirigir según el rol
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            echo "Credenciales incorrectas";
        }
        $stmt->close();
    }
    $db->close();
}
?>
