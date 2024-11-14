<?php
session_start();
include '../include/db.php';  // Incluir la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../manages/login.html");
    exit();
}

// Conectar a la base de datos
$db = new Database();
$conn = $db->conn;
$user_id = $_SESSION['user_id'];  // Obtener el ID del usuario de la sesión

// Obtener los datos del usuario desde la base de datos
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc(); // Asignar los datos del usuario a la variable $user

// Verificar si el usuario existe
if (!$user) {
    echo "No se encontraron datos para este usuario.";
    exit();
}

// Procesar formulario de edición, eliminación y cambio de contraseña
$update_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Procesar la actualización del perfil (nombre, correo, foto de perfil)
    if (isset($_POST['update_profile'])) {
        // Subir foto de perfil
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = __DIR__ . '/../uploads/profile_pictures/';
            $image_name = time() . "_" . $_FILES['profile_picture']['name'];
            $image_path = $upload_dir . $image_name;

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $image_path)) {
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $image_name, $user_id);
                if ($stmt->execute()) {
                    // Actualizamos también los comentarios
                    $stmt_update_comments = $conn->prepare("UPDATE comentarios SET profile_picture = ? WHERE user_id = ?");
                    $stmt_update_comments->bind_param("si", $image_name, $user_id);
                    $stmt_update_comments->execute();

                    $update_message = "Foto de perfil actualizada con éxito.";
                } else {
                    $update_message = "Error al actualizar la base de datos.";
                }
            } else {
                $update_message = "Error al cargar la imagen.";
            }
        }

        // Editar datos del usuario (username, email)
        if (isset($_POST['username'], $_POST['email'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];

            // Verificar que el correo electrónico no exista en otro usuario
            $stmt_email_check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt_email_check->bind_param("si", $email, $user_id);
            $stmt_email_check->execute();
            $email_check_result = $stmt_email_check->get_result();

            if ($email_check_result->num_rows > 0) {
                $update_message = "El correo electrónico ya está en uso.";
            } else {
                // Actualizar la información del usuario
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $username, $email, $user_id);
                if ($stmt->execute()) {
                    // Actualizamos también los comentarios
                    $stmt_update_comments = $conn->prepare("UPDATE comentarios SET username = ? WHERE user_id = ?");
                    $stmt_update_comments->bind_param("si", $username, $user_id);
                    $stmt_update_comments->execute();

                    $update_message = "Datos actualizados con éxito.";
                } else {
                    $update_message = "Error al actualizar los datos.";
                }
            }
        }
    }

    // Procesar el cambio de contraseña
    if (isset($_POST['update_password'])) {
        if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Verificar que la contraseña actual sea correcta
            if (password_verify($current_password, $user['password'])) {
                // Verificar que la nueva contraseña y la confirmación coincidan
                if ($new_password === $confirm_password) {
                    // Encriptar la nueva contraseña
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                    // Actualizar la contraseña en la base de datos
                    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->bind_param("si", $new_password_hashed, $user_id);
                    if ($stmt->execute()) {
                        $update_message = "Contraseña actualizada con éxito.";
                    } else {
                        $update_message = "Error al actualizar la contraseña.";
                    }
                } else {
                    $update_message = "Las contraseñas no coinciden.";
                }
            } else {
                $update_message = "La contraseña actual es incorrecta.";
            }
        }
    }

    // Eliminar cuenta
    if (isset($_POST['delete_account'])) {
        // Primero, eliminar los likes de comentarios del usuario
        $stmt = $conn->prepare("DELETE FROM comentarios_likes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Luego, eliminar los comentarios del usuario
        $stmt = $conn->prepare("DELETE FROM comentarios WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Eliminar la foto de perfil del servidor si existe
        if (!empty($user['profile_picture'])) {
            $profile_picture_path = __DIR__ . '/../uploads/profile_pictures/' . $user['profile_picture'];
            if (file_exists($profile_picture_path)) {
                unlink($profile_picture_path); // Eliminar la foto de perfil del servidor
            }
        }

        // Finalmente, eliminar al usuario de la base de datos
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // Cerrar la sesión y redirigir al login
            session_destroy();
            header("Location: ../manages/login.html");
            exit();
        } else {
            $update_message = "Error al eliminar la cuenta.";
        }
    }
}

// Cerrar la conexión después de las operaciones
$stmt->close();
$conn->close();
?>
