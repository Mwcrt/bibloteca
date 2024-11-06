<?php
session_start();

// Verificar si el usuario ha iniciado sesión y no es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../manages/login.html");
    exit();
}

echo "Bienvenido, " . $_SESSION['username'] . "!<br>";
echo "<a href='../manages/logout.php'>Cerrar sesión</a>";

// Aquí puedes agregar funcionalidades específicas para los usuarios comunes
?>
