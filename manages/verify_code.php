<?php
session_start();
include '../include/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el código de verificación ingresado
    $verificationCode = $_POST['verification_code'];
    $userId = $_SESSION['user_id']; // El ID de usuario guardado al hacer login

    try {
        // Crear la conexión a la base de datos
        $db = new Database();
        $conn = $db->conn;

        // Obtener el código de verificación guardado
        $stmt = $conn->prepare("SELECT verification_code FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedVerificationCode);
            $stmt->fetch();

            // Verificar si el código es correcto
            if ($verificationCode == $storedVerificationCode) {
                // El código es correcto, eliminarlo y activar la cuenta
                activarCuenta($conn, $userId);
                
                // Limpiar la sesión del usuario (evitar conflictos)
                session_unset();
                session_destroy();

                // Redirigir al login para que el usuario pueda iniciar sesión de nuevo
                header("Location: ../pages/auth_form.php");
                exit();
            } else {
                echo "El código de verificación es incorrecto.";
            }
        } else {
            echo "Usuario no encontrado o código inválido.";
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

/**
 * Activa la cuenta del usuario y limpia el código de verificación.
 *
 * @param object $conn - La conexión a la base de datos.
 * @param int $userId - El ID del usuario.
 */
function activarCuenta($conn, $userId) {
    // Eliminar el código de verificación y activar la cuenta
    $updateStmt = $conn->prepare("UPDATE users SET verification_code = NULL, is_active = 1 WHERE id = ?");
    $updateStmt->bind_param("i", $userId);
    if ($updateStmt->execute()) {
        echo "Cuenta activada exitosamente.";
    } else {
        echo "Error al activar la cuenta.";
    }
    $updateStmt->close();
}
?>
