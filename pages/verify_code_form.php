<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/verification.css">
    <title>Verificar Código de Activación</title>

</head>
<body>
    <!-- Modal para el formulario de verificación -->
    <div class="modal">
        <h2>Verificar Código de Activación</h2>

        <form id="verificationForm" method="POST" action="../manages/verify_code.php">
            <label for="verification_code">Código de Verificación:</label>
            <input type="text" id="verification_code" name="verification_code" required>
            <button type="submit" id="submitButton">Verificar</button>
        </form>
    </div>

    <!-- Contenedor del spinner, fuera del botón -->
    <div id="spinner" class="spinner"></div>

    <script src = "../js/verification.js"></script>

</body>
</html>
