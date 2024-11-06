<?php
// Incluir la conexión a la base de datos
include '../include/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validación básica
    $errors = [];

    if (empty($username)) {
        $errors[] = "El nombre de usuario es obligatorio.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Debe proporcionar un correo electrónico válido.";
    }

    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    // Si no hay errores, intentamos registrar el usuario
    if (empty($errors)) {
        try {
            // Crear la conexión a la base de datos
            $db = new Database();

            // Verificar si el usuario o correo ya existen
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = "El nombre de usuario o el correo electrónico ya están registrados.";
            } else {
                // Encriptar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario
                $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                // Verificar si se insertó correctamente
                if ($stmt->affected_rows > 0) {
                    echo "Usuario registrado exitosamente. <a href='../pages/login_form.php'>Iniciar sesión</a>";
                } else {
                    $errors[] = "Error al registrar el usuario. Inténtalo de nuevo.";
                }
            }

            $stmt->close();
            $db->close();
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }

    // Mostrar los errores si existen
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>
