<?php 
// Incluir la lógica para obtener los libros
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

<!-- Div contenedor principal -->
<div class="main-container">

    <!-- Incluir el menú -->
    <h2 class="titulo">Catálogos de Libros: Encuentra Todos Nuestros Títulos</h2>
    <p class="introduccion">
    Aquí encontrarás nuestros catálogos completos de libros, organizados para facilitar tu búsqueda. 
    Desde novelas emocionantes hasta guías prácticas, pasando por fascinantes textos de ciencia y cultura, 
    tenemos una amplia variedad para todos los gustos. Explora categorías como autoayuda, biografías, ciencia, 
    historia, literatura infantil y más, con recomendaciones y descripciones cuidadas para ayudarte a encontrar 
    tu próxima gran lectura. Sumérgete en este espacio de exploración literaria y descubre mundos llenos de historias y 
    conocimientos que te esperan. ¡Tu próxima aventura literaria está a solo un clic!
    </p>

    <!-- Mostrar mensaje de sesión si existe -->
    <?php if (isset($_SESSION['message'])) { 
        $message_type = htmlspecialchars($_SESSION['message'][0]); // success, warning, error
        $message_content = htmlspecialchars($_SESSION['message'][1]);
    ?>
        <!-- Popup Message -->
        <div class="popup-message <?php echo $message_type; ?>">
            <div class="popup-content">
                <p><?php echo $message_content; ?></p>
                <button class="close-btn">Cerrar</button>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <!-- Mostrar el total de libros disponibles si la variable existe -->
    <?php if (isset($total_books)) { ?>
        <h3 class="disponibles">Total de libros disponibles: <?php echo htmlspecialchars($total_books); ?></h3>
    <?php } else { ?>
        <h3 class="disponibles">Total de libros disponibles: No disponible</h3>
    <?php } ?>

    <!-- Contenedor para los libros -->
    <div class="books-container">
        <!-- Verificar si hay resultados de libros -->
        <?php if (isset($books_result) && $books_result->num_rows > 0) {
            while ($book = $books_result->fetch_assoc()) {

                // Inicializar el estado de reserva del libro
                $reservation_status = ''; 

                // 0: No disponible, 1: Reservado, 3: Disponible
                if ($book['available'] == 0) {
                    $reservation_status = 'unavailable';  // El libro no está disponible
                } elseif ($book['available'] == 1) {
                    // Si el libro está reservado, verificar si la fecha de expiración es válida
                    $reservation_status = 'reserved';  // El libro está reservado
                } elseif ($book['available'] == 3) {
                    // Si el libro está disponible
                    $reservation_status = 'available';  // El libro está disponible
                }
        ?>
            <div class="book <?php echo $reservation_status; ?>">
                <!-- Mostrar la tira de estado si el libro no está disponible o está reservado -->
                <?php if ($reservation_status == 'reserved') { ?>
                    <div class="status-strip yellow">¡Este libro está reservado!</div>
                <?php } elseif ($reservation_status == 'unavailable') { ?>
                    <div class="status-strip red">¡Este libro no está disponible!</div>
                <?php } elseif ($reservation_status == 'available') { ?>
                    <div class="status-strip green">¡Este libro está disponible!</div>
                <?php } ?>

                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($book['category']); ?></p>

                <!-- Descripción y Fecha de Publicación se ocultan por defecto -->
                <p class="description" style="display: none;">
                    <strong>Descripción:</strong> <?php echo htmlspecialchars($book['description']); ?>
                </p>
                <p class="publication-date" style="display: none;">
                    <strong>Publicación:</strong> <?php echo htmlspecialchars($book['publication_date']); ?>
                </p>

                <!-- Mostrar imagen del libro si existe -->
                <?php if (!empty($book['image_url'])) {
                    $image_path = 'uploads/' . basename($book['image_url']);
                ?>
                    <img src="/bibloteca/<?php echo htmlspecialchars($image_path); ?>" alt="Imagen del libro <?php echo htmlspecialchars($book['title']); ?>">
                <?php } else { ?>
                    <p>Sin imagen disponible</p>
                <?php } ?>
                
                <!-- Formulario para reservar el libro (solo si está disponible) -->
                <?php if ($reservation_status == 'available') { ?>
                    <form action="book_form.php" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
                        <button type="submit">Reservar Libro</button>
                    </form>
                <?php } ?>

                <!-- Botón para ver los detalles del libro -->
                <button class="toggle-details">Ver Detalles</button>
            </div>
        <?php
            } // Cerrar el bucle while
        } else { // Si no hay libros disponibles
            echo '<p>No hay libros disponibles para reservar en este momento.</p>';
        } 
        ?>

        <!-- Botones de paginación -->
        <div class="pagination">
            <button id="prev" disabled>Anterior</button>
            <button id="next">Siguiente</button>
        </div>
    </div>

</div> <!-- Fin del div main-container -->

<!-- Incluir el archivo JS -->
<script src="../js/books.js"></script>
</body>
</html>
