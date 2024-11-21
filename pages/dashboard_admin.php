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
        include '../manages/dashboard_admin_manages.php';
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
        <select id="category" name="category" required>
            <option value="Ficción Contemporánea">Ficción Contemporánea</option>
            <option value="Fantasía">Fantasía</option>
            <option value="Ciencia Ficción">Ciencia Ficción</option>
            <option value="Romance">Romance</option>
            <option value="Misterio y Suspenso">Misterio y Suspenso</option>
            <option value="Terror">Terror</option>
            <option value="Autobiografías y Biografías">Autobiografías y Biografías</option>
            <option value="Desarrollo Personal y Autoayuda">Desarrollo Personal y Autoayuda</option>
            <option value="No Ficción">No Ficción</option>
            <option value="Clásicos">Clásicos</option>
            <option value="Aventura">Aventura</option>
            <option value="Ficción Histórica">Ficción Histórica</option>
            <option value="Poesía">Poesía</option>
            <option value="Cómics y Novelas Gráficas">Cómics y Novelas Gráficas</option>
            <option value="Infantil y Juvenil">Infantil y Juvenil</option>
        </select><br><br>

        <label for="publication_date">Fecha de Publicación:</label><br>
        <input type="date" id="publication_date" name="publication_date" required><br><br>

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
        <select id="category" name="category" required>
            <option value="Ficción Contemporánea" <?php if ($book['category'] == 'Ficción Contemporánea') echo 'selected'; ?>>Ficción Contemporánea</option>
            <option value="Fantasía" <?php if ($book['category'] == 'Fantasía') echo 'selected'; ?>>Fantasía</option>
            <option value="Ciencia Ficción" <?php if ($book['category'] == 'Ciencia Ficción') echo 'selected'; ?>>Ciencia Ficción</option>
            <option value="Romance" <?php if ($book['category'] == 'Romance') echo 'selected'; ?>>Romance</option>
            <option value="Misterio y Suspenso" <?php if ($book['category'] == 'Misterio y Suspenso') echo 'selected'; ?>>Misterio y Suspenso</option>
            <option value="Terror" <?php if ($book['category'] == 'Terror') echo 'selected'; ?>>Terror</option>
            <option value="Autobiografías y Biografías" <?php if ($book['category'] == 'Autobiografías y Biografías') echo 'selected'; ?>>Autobiografías y Biografías</option>
            <option value="Desarrollo Personal y Autoayuda" <?php if ($book['category'] == 'Desarrollo Personal y Autoayuda') echo 'selected'; ?>>Desarrollo Personal y Autoayuda</option>
            <option value="No Ficción" <?php if ($book['category'] == 'No Ficción') echo 'selected'; ?>>No Ficción</option>
            <option value="Clásicos" <?php if ($book['category'] == 'Clásicos') echo 'selected'; ?>>Clásicos</option>
            <option value="Aventura" <?php if ($book['category'] == 'Aventura') echo 'selected'; ?>>Aventura</option>
            <option value="Ficción Histórica" <?php if ($book['category'] == 'Ficción Histórica') echo 'selected'; ?>>Ficción Histórica</option>
            <option value="Poesía" <?php if ($book['category'] == 'Poesía') echo 'selected'; ?>>Poesía</option>
            <option value="Cómics y Novelas Gráficas" <?php if ($book['category'] == 'Cómics y Novelas Gráficas') echo 'selected'; ?>>Cómics y Novelas Gráficas</option>
            <option value="Infantil y Juvenil" <?php if ($book['category'] == 'Infantil y Juvenil') echo 'selected'; ?>>Infantil y Juvenil</option>
        </select><br><br>

        <label for="publication_date">Fecha de Publicación:</label><br>
        <input type="date" id="publication_date" name="publication_date" value="<?php echo $book['publication_date']; ?>" required><br><br>

        <label for="available">Disponible:</label><br>
        <input type="checkbox" id="available" name="available" <?php echo $book['available'] ? 'checked' : ''; ?>><br><br>

        <label for="image">Imagen:</label><br>
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
