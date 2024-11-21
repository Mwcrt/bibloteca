<?php 
session_start();
include '../include/menubar.php';


function checkUserSession() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header("Location: ../manages/login.html");
        exit();
    }
}


function getDatabaseConnection() {
    include '../include/db.php';
    $db = new Database();
    return $db->conn;
}

function getBooks($conn) {
    $sql = "SELECT title, image_url FROM books ORDER BY RAND()";
    $result = $conn->query($sql);
    $books = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}

checkUserSession();

$conn = getDatabaseConnection();

$books = getBooks($conn);
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
        <!-- Mostrar el mensaje de bienvenida -->
        <h1><span id="welcome-message"></span></h1>
        <p>Aquí puedes gestionar tu información, ver libros disponibles, hacer reservas, y más.</p>

        <!-- Tira deslizante de libros -->
        <div class="slider-container">
            <div class="slider" id="book-slider">
                <?php if (!empty($books)) {
                    foreach ($books as $book) {
                        $image_path = !empty($book['image_url']) ? 'uploads/' . basename($book['image_url']) : null;
                        echo "<div class='book-item'>";
                        echo $image_path ? "<img src='/bibloteca/{$image_path}' alt='Imagen del libro {$book['title']}'>" : "<p>Sin imagen disponible</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay imágenes disponibles en este momento.</p>";
                } ?>
            </div>
        </div>

        <!-- Sección sobre nosotros -->
        <div class="about-us-container" id="about-us">
            <h2>Sobre Nosotros</h2>
            <p>Bienvenido a <strong>Library</strong>, tu librería virtual y plataforma de reservas. En Library, ofrecemos una amplia selección de libros para todas las edades e intereses...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var username = "<?php echo $_SESSION['username']; ?>";
            var welcomeText = "Bienvenido, " + username + "!";
            var welcomeMessageElement = document.getElementById('welcome-message');
            var index = 0;

            function typeWriter() {
                if (index < welcomeText.length) {
                    var span = document.createElement('span');
                    span.textContent = welcomeText.charAt(index);
                    span.classList.add('letter');
                    welcomeMessageElement.appendChild(span);
                    index++;
                    setTimeout(typeWriter, 60);
                }
            }

            typeWriter();
        });
    </script>
</body>
</html>
