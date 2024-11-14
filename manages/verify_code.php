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

        // Obtener el código de verificación guardado
        $stmt = $db->prepare("SELECT verification_code FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedVerificationCode);
            $stmt->fetch();

            // Verificar si el código es correcto
            if ($verificationCode == $storedVerificationCode) {
                // El código es correcto, eliminarlo y activar la cuenta
                $updateStmt = $db->prepare("UPDATE users SET verification_code = NULL, is_active = 1 WHERE id = ?");
                $updateStmt->bind_param("i", $userId);
                $updateStmt->execute();

                // Limpiar la sesión del usuario (evitar conflictos)
                session_unset();
                session_destroy();
                
                // Redirigir al login para que el usuario pueda iniciar sesión de nuevo
                header("Location: ../pages/auth_form.php");
                exit();
            } else {
                echo "El código de verificación es incorrecto.";
            }
        }

        $stmt->close();
        $db->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
