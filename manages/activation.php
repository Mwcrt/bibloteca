<?php
// Incluir la conexión a la base de datos
include '../include/db.php';

// Verificar si el código de activación está presente en la URL
if (isset($_GET['code'])) {
    $activationCode = $_GET['code'];

    try {
        // Crear la conexión a la base de datos
        $conn = $db->conn;

        // Verificar si el código de activación es válido y no ha sido usado antes
        if (esCodigoValido($conn, $activationCode)) {
            // Activar la cuenta
            if (activarCuenta($conn, $activationCode)) {
                echo "Tu cuenta ha sido activada con éxito. Ahora puedes <a href='../pages/login_form.php'>iniciar sesión</a>.";
            } else {
                echo "Hubo un error al activar tu cuenta.";
            }
        } else {
            echo "Código de activación inválido o ya utilizado.";
        }

        // Cerrar la conexión
        $conn->close();
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se proporcionó un código de activación.";
}

/**
 * Verifica si el código de activación es válido.
 *
 * @param object $conn - La conexión a la base de datos.
 * @param string $activationCode - El código de activación.
 * @return bool - True si el código es válido, false si no lo es.
 */
function esCodigoValido($conn, $activationCode) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE activation_code = ? AND is_active = 0");
    $stmt->bind_param("s", $activationCode);
    $stmt->execute();
    $stmt->store_result();

    $isValid = $stmt->num_rows > 0;
    $stmt->close();

    return $isValid;
}

/**
 * Activa la cuenta del usuario y limpia el código de activación.
 *
 * @param object $conn - La conexión a la base de datos.
 * @param string $activationCode - El código de activación.
 * @return bool - True si la cuenta fue activada correctamente, false si hubo un error.
 */
function activarCuenta($conn, $activationCode) {
    $stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = ?");
    $stmt->bind_param("s", $activationCode);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
?>
