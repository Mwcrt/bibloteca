<?php 
// Incluir la lógica para obtener los libros y el menú
include '../manages/search.php'; 
include '../include/menubar.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/books.css">
    <title>Libros Disponibles</title>
</head>
<body>

<div class="main-container">
    <div class ="presentacion-book">
        <h2 class="titulo">Catálogos de Libros: Encuentra Todos Nuestros Títulos</h2>
            <p class="introduccion">
                Aquí encontrarás nuestros catálogos completos de libros, organizados para facilitar tu búsqueda. 
                Desde novelas emocionantes hasta guías prácticas, pasando por fascinantes textos de ciencia y cultura, 
                tenemos una amplia variedad para todos los gustos. Explora categorías como autoayuda, biografías, ciencia, 
                historia, literatura infantil y más, con recomendaciones y descripciones cuidadas para ayudarte a encontrar 
                tu próxima gran lectura. ¡Tu próxima aventura literaria está a solo un clic!
            </p>
    
            <!-- Mostrar total de libros disponibles -->
            <h3 class="disponibles">
                Total de libros disponibles: <?php echo isset($total_books) ? htmlspecialchars($total_books) : 'No disponible'; ?>
            </h3>
    </div>

    <!-- Mostrar mensaje de sesión si existe -->
    <?php if (isset($_SESSION['message'])): 
        list($message_type, $message_content) = $_SESSION['message']; 
        unset($_SESSION['message']);
    ?>
        <div class="popup-message <?php echo htmlspecialchars($message_type); ?>">
            <div class="popup-content">
                <p><?php echo htmlspecialchars($message_content); ?></p>
                <button class="close-btn">Cerrar</button>
            </div>
        </div>
    <?php endif; ?>

    

    <!-- Contenedor para los libros -->
    <div class="books-container">
        <?php if (isset($books_result) && $books_result->num_rows > 0): 
            while ($book = $books_result->fetch_assoc()):
                $reservation_status = getReservationStatus($book['available']);
        ?>
            <div class="book <?php echo $reservation_status; ?>">

                <!-- Estado del libro -->
                <?php echo getBookStatusStrip($reservation_status); ?>

                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($book['category']); ?></p>

                <!-- Descripción y fecha de publicación ocultos por defecto -->
                <p class="description" style="display: none;">
                    <strong>Descripción:</strong> <?php echo htmlspecialchars($book['description']); ?>
                </p>
                <p class="publication-date" style="display: none;">
                    <strong>Publicación:</strong> <?php echo htmlspecialchars($book['publication_date']); ?>
                </p>

                <!-- Mostrar imagen del libro -->
                <?php echo getBookImage($book['image_url'], $book['title']); ?>

                <!-- Formulario de reserva solo si está disponible -->
                <?php if ($reservation_status == 'available'): ?>
                    <form action="book_form.php" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
                        <button type="submit">Reservar Libro</button>
                    </form>
                <?php endif; ?>

                <!-- Botón para ver detalles del libro -->
                <button class="toggle-details">Ver Detalles</button>
            </div>
        <?php endwhile; else: ?>
            <p>No hay libros disponibles para reservar en este momento.</p>
        <?php endif; ?>

        <!-- Botones de paginación -->
        <div class="pagination">
            <button id="prev" disabled>Anterior</button>
            <button id="next">Siguiente</button>
        </div>
    </div>

</div>

<script src="../js/books.js"></script>

</body>
</html>

<?php
// Funciones auxiliares

// Obtener el estado de la reserva del libro
function getReservationStatus($availability) {
    switch ($availability) {
        case 0:
            return 'unavailable';
        case 1:
            return 'reserved';
        case 3:
            return 'available';
        default:
            return '';
    }
}

// Mostrar la tira de estado del libro
function getBookStatusStrip($reservation_status) {
    $status_message = '';
    switch ($reservation_status) {
        case 'reserved':
            $status_message = '<div class="status-strip yellow">¡Este libro está reservado!</div>';
            break;
        case 'unavailable':
            $status_message = '<div class="status-strip red">¡Este libro no está disponible!</div>';
            break;
        case 'available':
            $status_message = '<div class="status-strip green">¡Este libro está disponible!</div>';
            break;
    }
    return $status_message;
}

// Obtener la imagen del libro
function getBookImage($image_url, $title) {
    if (!empty($image_url)) {
        $image_path = 'uploads/' . basename($image_url);
        return '<img src="/bibloteca/' . htmlspecialchars($image_path) . '" alt="Imagen del libro ' . htmlspecialchars($title) . '">';
    } else {
        return '<p>Sin imagen disponible</p>';
    }
}
?>
