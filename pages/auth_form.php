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
        <form action="../manages/auth.php" method="POST">
            <h1>Create Account</h1>
            <div class="input-group">
                <input type="text" name="username" placeholder="Name" required />
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
            </div>
            <button type="submit" name="register">Sign Up</button>
        </form>
    </div>

    <!-- Formulario de Inicio de Sesión -->
    <div class="form-container sign-in-container">
        <form action="../manages/auth.php" method="POST">
            <h1>Sign In</h1>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>

    <!-- Parte de la Interfaz para cambiar entre Login y Registro -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To stay updated, sign in and see what's new!</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Sign up and enjoy our wide range of titles!</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/login.js"></script>

</body>
</html>
