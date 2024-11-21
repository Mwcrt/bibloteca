<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fondo Estilo Lámpara de Lava</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #000;
        }

        .lampara-lava {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .burbuja {
            position: absolute;
            background: radial-gradient(circle, rgba(255, 0, 100, 0.8) 0%, rgba(255, 0, 100, 0.5) 40%, transparent 70%);
            border-radius: 50%;
            animation: movimientoBurbuja 20s infinite ease-in-out;
            opacity: 0.7;
        }

        @keyframes movimientoBurbuja {
            0% {
                transform: translateY(100vh) scale(0.8);
            }
            50% {
                transform: translateY(-20vh) scale(1.2);
            }
            100% {
                transform: translateY(100vh) scale(0.8);
            }
        }

        /* Crear varias burbujas con diferentes tamaños y posiciones */
        .burbuja:nth-child(1) {
            width: 200px;
            height: 200px;
            left: 10%;
            animation-duration: 25s;
        }

        .burbuja:nth-child(2) {
            width: 150px;
            height: 150px;
            left: 40%;
            animation-duration: 20s;
            animation-delay: 5s;
        }

        .burbuja:nth-child(3) {
            width: 250px;
            height: 250px;
            left: 70%;
            animation-duration: 30s;
            animation-delay: 10s;
        }

        .burbuja:nth-child(4) {
            width: 100px;
            height: 100px;
            left: 25%;
            animation-duration: 18s;
            animation-delay: 2s;
        }

        .burbuja:nth-child(5) {
            width: 180px;
            height: 180px;
            left: 55%;
            animation-duration: 22s;
            animation-delay: 7s;
        }
    </style>
</head>
<body>
    <div class="lampara-lava">
        <div class="burbuja"></div>
        <div class="burbuja"></div>
        <div class="burbuja"></div>
        <div class="burbuja"></div>
        <div class="burbuja"></div>
    </div>
</body>
</html>
