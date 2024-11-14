<?php
session_start();
include '../include/db.php';
include '../include/menubar.php';  // Incluir la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../manages/login.html");
    exit();
}

$db = new Database();
$conn = $db->conn;

// Obtener los libros disponibles
$sql = "SELECT title, image_url FROM books ORDER BY RAND()";  // Traer todos los libros disponibles (puedes agregar limitaciones si es necesario)
$result = $conn->query($sql);
$books = [];  // Inicializar el array de libros

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuario</title>
    <link rel="stylesheet" href="../css/dashboard.css">
   
</head>
<body>
    <div class="container">
        <?php
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            header("Location: ../manages/login.html");
            exit();
        }

        // Almacenar el nombre de usuario en una variable de JavaScript
        echo "<h1><span id='welcome-message'></span></h1>";
        ?> 
        <p>Aquí puedes gestionar tu información, ver libros disponibles, hacer reservas, y más.</p>

        <!-- Tira deslizante de libros -->
        <div class="slider-container">
            <div class="slider" id="book-slider">
                <?php
                // Verificar si hay libros disponibles antes de intentar mostrarlos
                if (!empty($books)) {
                    foreach ($books as $book) {
                        // Verificar si hay imagen
                        if (!empty($book['image_url'])) {
                            $image_path = 'uploads/' . basename($book['image_url']);
                            echo "<img src='/bibloteca/" . htmlspecialchars($image_path) . "' alt='Imagen del libro " . htmlspecialchars($book['title']) . "'>";
                        } else {
                            echo "<p>Sin imagen disponible</p>";
                        }
                    }
                } else {
                    echo "<p>No hay imágenes disponibles en este momento.</p>";
                }
                ?>
            </div>
        </div>

        <div class="about-us-container" id="about-us">
    <h2>Sobre Nosotros</h2>
    <p>Bienvenido a <strong>Library</strong>, tu librería virtual y plataforma de reservas. En Library, ofrecemos una amplia selección de libros para todas las edades e intereses. Ya sea que busques el último bestseller o una novela clásica, puedes explorar fácilmente nuestra colección y reservar los libros que amas.</p>
    <p>Nuestra plataforma te permite hacer reservas en línea, asegurando que nunca te pierdas una gran lectura. ¡Experimenta el futuro de la lectura con Library!</p>
    </div>
    </div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Nombre del usuario desde PHP
            var username = "<?php echo $_SESSION['username']; ?>";
            var welcomeText = "Bienvenido, " + username + "!";
            var welcomeMessageElement = document.getElementById('welcome-message');
            var index = 0;

            // Función para escribir el texto letra por letra, envolviendo cada letra en un <span>
            function typeWriter() {
                if (index < welcomeText.length) {
                    var span = document.createElement('span');
                    span.textContent = welcomeText.charAt(index);
                    span.classList.add('letter'); // Añadir clase a cada letra
                    welcomeMessageElement.appendChild(span);
                    index++;
                    setTimeout(typeWriter, 60);
                }
            }

            // Iniciar la animación de escritura
            typeWriter();
        });
    </script>
</body>
</html>