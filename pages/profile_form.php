<?php include '../manages/profile.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <!-- Enlazar el archivo CSS -->
    <link rel="stylesheet" href="../css/pic.css">
    <!-- Google Material Icons y fuente para Material Design -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <!-- Incluir el menú, que será fijo -->
        <div id="menu-container">
            <?php include '../include/menubar.php';?>  
        </div>

        <!-- Contenedor del perfil -->
        <div class="profile-container">
            <h1>Mi Perfil</h1>
            <!-- Mostrar foto de perfil -->
            <div class="profile-picture-section">
                <?php if (isset($user['profile_picture']) && $user['profile_picture']) { ?>
                    <img src="../uploads/profile_pictures/<?php echo $user['profile_picture']; ?>" alt="Foto de perfil" width="150">
                <?php } else { ?>
                    <p>No tienes una foto de perfil.</p>
                <?php } ?>
            </div>

            <div class="user-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <button type="button" class="material-button" id="toggleProfile">
                Editar Mi Perfil
            </button>

            <!-- Secciones ocultas -->
            <div id="editSections" style="display: none;">
                <!-- Editar Perfil -->
                <div id="editProfileSection">
                    <h2>Editar Perfil</h2>
                    <form action="profile_form.php" method="POST" enctype="multipart/form-data">
                        <label for="username">Nuevo Nombre:</label>
                        <input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : (isset($user['username']) ? $user['username'] : ''); ?>" required><br>

                        <label for="email">Nuevo Correo Electrónico:</label>
                        <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : (isset($user['email']) ? $user['email'] : ''); ?>" required><br>

                        <label for="profile_picture">Subir nueva foto de perfil:</label>
                        <input type="file" name="profile_picture" accept="image/*"><br>

                        <button type="submit" class="material-button" name="update_profile">Actualizar Perfil</button>
                    </form>
                </div>

                <!-- Cambiar Contraseña -->
                <div id="changePasswordSection">
                    <h2>Cambiar Contraseña</h2>
                    <form action="profile_form.php" method="POST">
                        <label for="current_password">Contraseña Actual:</label>
                        <input type="password" name="current_password" required><br>

                        <label for="new_password">Nueva Contraseña:</label>
                        <input type="password" name="new_password" required><br>

                        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" name="confirm_password" required><br>

                        <button type="submit" class="material-button" name="update_password">Cambiar Contraseña</button>
                    </form>
                </div>

                <!-- Eliminar Cuenta -->
                <div id="deleteAccountSection">
                    <h2>Eliminar Cuenta</h2>
                    <form action="profile_form.php" method="POST">
                        <p>¿Seguro que quieres eliminar tu cuenta?</p>
                        <button type="submit" class="material-button danger" name="delete_account">Eliminar Cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/profile.js" defer></script>
</body>
</html>
