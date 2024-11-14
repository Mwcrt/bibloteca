<?php
// Incluir la conexión a la base de datos
include '../include/db.php';

if (isset($_GET['code'])) {
    $activationCode = $_GET['code'];

    // Verificar si el código de activación es válido y no ha sido usado antes
    $stmt = $db->prepare("SELECT id FROM users WHERE activation_code = ? AND is_active = 0");
    $stmt->bind_param("s", $activationCode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Activar la cuenta
        $stmt = $db->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = ?");
        $stmt->bind_param("s", $activationCode);
        $stmt->execute();

        echo "Tu cuenta ha sido activada con éxito. Ahora puedes <a href='../pages/login_form.php'>iniciar sesión</a>.";
    } else {
        echo "Código de activación inválido o ya utilizado.";
    }

    $stmt->close();
    $db->close();
} else {
    echo "No se proporcionó un código de activación.";
}
?>
