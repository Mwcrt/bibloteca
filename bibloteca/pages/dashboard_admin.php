<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Dashboard del Administrador</h1>

    <?php
        include'../manages/dashboard_admin_manages.php';
    ?>

    <!-- Formulario para crear un libro -->
    <h2>Agregar Nuevo Libro</h2>
    <form action="dashboard_admin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="create_book" value="1">
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="author">Autor:</label><br>
        <input type="text" id="author" name="author" required><br><br>

        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description"></textarea><br><br>

        <label for="category">Categoría:</label><br>
        <input type="text" id="category" name="category"><br><br>

        <label for="available">Disponible:</label><br>
        <input type="checkbox" id="available" name="available" checked><br><br>

        <label for="image">Imagen:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <button type="submit">Agregar Libro</button>
    </form>
    
    <!-- Formulario para editar un libro -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];

        // Obtener los detalles del libro a editar
        $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();
    ?>
    <h2>Editar Libro</h2>
    <form action="dashboard_admin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="edit_book" value="1">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required><br><br>

        <label for="author">Autor:</label><br>
        <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required><br><br>

        <label for="description">Descripción:</label><br>
        <textarea id="description" name="description"><?php echo $book['description']; ?></textarea><br><br>

        <label for="category">Categoría:</label><br>
        <input type="text" id="category" name="category" value="<?php echo $book['category']; ?>"><br><br>

        <label for="available">Disponible:</label><br>
        <input type="checkbox" id="available" name="available" <?php echo $book['available'] ? 'checked' : ''; ?>><br><br>

        <label for="image">Imagen:</label><br>
        <!-- Mostrar la imagen actual si existe -->
        <?php if ($book['image_url']): ?>
        <?php endif; ?>
        <input type="file" id="image" name="image"><br><br>

        <button type="submit">Actualizar Libro</button>
    </form>
    <?php } ?>
    
    <!-- Tabla de libros con opción para borrar y editar -->
    <h2>Lista de Libros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Disponible</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener los libros de la base de datos
            $stmt = $conn->prepare("SELECT * FROM books");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($book = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $book['id'] . "</td>";
                echo "<td>" . $book['title'] . "</td>";
                echo "<td>" . $book['author'] . "</td>";
                echo "<td>" . $book['category'] . "</td>";
                echo "<td>" . ($book['available'] ? 'Sí' : 'No') . "</td>";

                // Mostrar la imagen si existe
                echo "<td>";
                if ($book['image_url']) {
                    // Aquí debes asegurarte de que la ruta sea relativa desde la raíz del proyecto
                    $image_path = 'uploads/' . basename($book['image_url']);
                    echo "<img src='/bibloteca/" . $image_path . "' alt='Imagen del libro' width='100'>";
                } else {
                    echo "Sin imagen";
                }
                echo "</td>";

                // Agregar los botones de Editar y Eliminar
                echo "<td>
                        <a href='dashboard_admin.php?delete_id=" . $book['id'] . "'>Eliminar</a> | 
                        <a href='dashboard_admin.php?edit_id=" . $book['id'] . "'>Editar</a>
                      </td>";
                echo "</tr>";
            }
            $stmt->close();
            ?>
        </tbody>
    </table>
</body>
</html>
