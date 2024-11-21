<?php
session_start();
include '../include/db.php';
include '../vendor/autoload.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function sendConfirmationEmail($userEmail, $userName, $verificationCode) {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'whoismiwelbruh@gmail.com';
            $mail->Password   = 'xbyo vhvo gcbt qpii';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('whoismiwelbruh@gmail.com', 'MIwiLibrary');
            $mail->addAddress($userEmail, $userName);

            $mail->isHTML(true);
            $mail->Subject = 'Código de verificación de cuenta';
            $mail->Body    = "<h1>Hola $userName,</h1><p>Usa el siguiente código para activar tu cuenta: <strong>$verificationCode</strong></p>";
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password, role, is_active FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password, $role, $is_active);
            $stmt->fetch();

            if ($is_active == 0) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: ../pages/verify_code_form.php");
                exit();
            } elseif (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: ../pages/dashboard_" . ($role === 'admin' ? 'admin' : 'user') . ".php");
                exit();
            } else {
                throw new Exception("La contraseña es incorrecta.");
            }
        } else {
            throw new Exception("No se encontró una cuenta con ese correo electrónico.");
        }
    }

    public function register($username, $email, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            throw new Exception("Las contraseñas no coinciden.");
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception("El nombre de usuario o el correo electrónico ya están registrados.");
        } else {
            $verificationCode = rand(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $verificationCode);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $this->sendConfirmationEmail($email, $username, $verificationCode);
                return "Usuario registrado exitosamente. Revisa tu correo para obtener el código de verificación.";
            } else {
                throw new Exception("Error al registrar el usuario. Inténtalo de nuevo.");
            }
        }
    }
}

$auth = new Auth();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['login'])) {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $auth->login($email, $password);
        } elseif (isset($_POST['register'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            echo $auth->register($username, $email, $password, $confirm_password);
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
