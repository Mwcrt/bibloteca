<?php
// Iniciar la sesión
session_start();

// Incluir la conexión a la base de datos
include '../include/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validación básica
    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Debe proporcionar un correo electrónico válido.";
    }

    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria.";
    }

    // Si no hay errores, intentamos iniciar sesión
    if (empty($errors)) {
        try {
            // Crear la conexión a la base de datos
            $db = new Database();

            // Preparar la consulta para obtener el usuario por email
            $stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Verificar si el usuario existe
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                $stmt->fetch();

                // Verificar si la contraseña ingresada coincide con la encriptada
                if (password_verify($password, $hashed_password)) {
                    // Iniciar la sesión del usuario
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    // Redirigir según el rol del usuario
                    if ($role === 'admin') {
                        // Si es admin, redirigir al panel de administración
                        header("Location: ../pages/dashboard_admin.php");
                    } else {
                        // Si es usuario común, redirigir al panel de usuario
                        header("Location: ../pages/dashboard_user.php");
                    }
                    exit();
                } else {
                    $errors[] = "La contraseña es incorrecta.";
                }
            } else {
                $errors[] = "No se encontró una cuenta con ese correo electrónico.";
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
