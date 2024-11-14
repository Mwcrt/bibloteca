<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Sign Up</title>
</head>
<body>

<div class="container" id="container">
    <!-- Formulario de Registro -->
    <div class="form-container sign-up-container">
        <form action="../manages/auth.php" method="POST">  <!-- Cambiar la ruta al script PHP -->
            <h1>Create Account</h1>
            <input type="text" name="username" placeholder="Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
            <!-- Botón de registro -->
            <button type="submit" name="register">Sign Up</button>
        </form>
    </div>

    <!-- Formulario de Inicio de Sesión -->
    <div class="form-container sign-in-container">
        <form action="../manages/auth.php" method="POST">  <!-- Cambiar la ruta al script PHP -->
            <h1>Sign in</h1>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <!-- Botón de inicio de sesión -->
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>

    <!-- Parte de la Interfaz para cambiar entre Login y Registro -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>Para mantenerte al tanto, inicia sesion y mira que hay de nuevo!</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Registrate y disfruta de nuestra gran variedad de titulos!</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

        <script src="../js/login.js"></script>
</body>
</html>