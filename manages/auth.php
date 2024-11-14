<?php
// Iniciar la sesión
session_start();

// Incluir la conexión a la base de datos y el autoload de PHPMailer
include '../include/db.php';
include '../vendor/autoload.php'; // Asegúrate de incluir PHPMailer

// Función para enviar el correo de verificación
function sendConfirmationEmail($userEmail, $userName, $verificationCode) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'whoismiwelbruh@gmail.com'; // Cambia por tu correo de Gmail
        $mail->Password   = 'xbyo vhvo gcbt qpii'; // Contraseña de aplicación si tienes 2FA
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom('whoismiwelbruh@gmail.com', 'MIwiLibrary');
        $mail->addAddress($userEmail, $userName); // Destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Codigo de verificacion de cuenta';
        $mail->Body    = "<h1>Hola $userName,</h1><p>Gracias por registrarte, se bienvenido a mi primera pagina web 😝. Usa el siguiente código para activar tu cuenta:</p><p><strong>$verificationCode</strong></p>";
        $mail->AltBody = "Hola $userName, usa el siguiente código para activar tu cuenta: $verificationCode";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Detectar si el formulario es de inicio de sesión o registro
    if (isset($_POST['login'])) {
        // INICIO DE SESIÓN
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        $errors = [];
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Debe proporcionar un correo electrónico válido.";
        }

        if (empty($password)) {
            $errors[] = "La contraseña es obligatoria.";
        }

        if (empty($errors)) {
            try {
                // Crear la conexión a la base de datos
                $db = new Database();

                // Preparar la consulta para obtener el usuario por email
                $stmt = $db->prepare("SELECT id, username, password, role, is_active FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                // Verificar si el usuario existe
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $username, $hashed_password, $role, $is_active);
                    $stmt->fetch();

                    // Verificar si la cuenta está activa
                    if ($is_active == 0) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        header("Location: ../pages/verify_code_form.php");
                        exit();
                    } else {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION['user_id'] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $role;

                            // Redirigir según el rol
                            if ($role === 'admin') {
                                header("Location: ../pages/dashboard_admin.php");
                            } else {
                                header("Location: ../pages/dashboard_user.php");
                            }
                            exit();
                        } else {
                            $errors[] = "La contraseña es incorrecta.";
                        }
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

        // Redirigir a login_form.php con errores si los hay
        if (!empty($errors)) {
            $error_message = urlencode(implode(', ', $errors));
            header("Location: ../pages/auth_form.php?error=$error_message");
            exit();
        }
    } elseif (isset($_POST['register'])) {
        // REGISTRO
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

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
                    // Generar código de verificación
                    $verificationCode = rand(100000, 999999);

                    // Encriptar la contraseña
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insertar el nuevo usuario en la base de datos
                    $stmt = $db->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $email, $hashed_password, $verificationCode);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        sendConfirmationEmail($email, $username, $verificationCode);
                        echo "Usuario registrado exitosamente. Revisa tu correo para obtener el código de verificación.";
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
}
?>
