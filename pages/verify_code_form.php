<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código de Activación</title>
    <style>
        /* Estilos generales de la página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgba(0, 0, 0, 0.6); /* Fondo oscuro */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Estilo del modal */
        .modal {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Estilo del encabezado */
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Estilo de la etiqueta y el input */
        label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            margin-top:15px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #5a9;
            box-shadow: 0 0 8px rgba(90, 169, 137, 0.4);
        }

        /* Estilo del botón */
        button {
            background-color: #5a9;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: #489874;
        }

        /* Estilo del botón durante la carga */
        button.loading {
            background-color: #fff;
            border: 2px solid #5a9;
            color: #5a9;
            pointer-events: none;
        }

        /* Estilo del spinner fuera del botón */
        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            border: 3px solid transparent;
            border-top: 3px solid #5a9;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            transform: translate(-50%, -50%);
            visibility: hidden; /* Inicialmente oculto */
        }

        /* Animación de giro para el spinner */
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
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

    <script>
        // JavaScript para animar el botón de carga
        const form = document.getElementById('verificationForm');
        const button = document.getElementById('submitButton');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', function(event) {
            // Evitar envío instantáneo para mostrar la animación
            event.preventDefault();

            // Cambiar el botón a estado de carga
            button.classList.add('loading');
            
            // Mostrar el spinner
            spinner.style.visibility = 'visible';

            // Simulación de un retardo antes de enviar el formulario
            setTimeout(() => {
                form.submit(); // Envía el formulario después de la animación
            }, 2000); // Puedes ajustar el tiempo según lo que desees
        });
    </script>
</body>
</html>
