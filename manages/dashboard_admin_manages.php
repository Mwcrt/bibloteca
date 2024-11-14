<?php
session_start();
include '../include/db.php';

// Verificar que el usuario esté autenticado y sea administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
    exit();
}

echo "Bienvenido al panel de administración, " . $_SESSION['username'] . "!<br>";
echo "<a href='../manages/logout.php'>Cerrar sesión</a>";

$db = new Database();
$conn = $db->conn;

// Lógica para crear un libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $available = isset($_POST['available']) ? 1 : 0;
    $publication_date = $_POST['publication_date']; // Obtener la fecha de publicación
    $image_url = null;

    // Subir la imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Usar una ruta absoluta para la carpeta de imágenes
        $image_dir = __DIR__ . '/../uploads/';
        $image_name = time() . "_" . $_FILES['image']['name'];
        $image_path = $image_dir . $image_name;

        // Verificar si la carpeta uploads/ existe, si no, crearla
        if (!file_exists($image_dir)) {
            mkdir($image_dir, 0777, true);  // Crear la carpeta si no existe
        }

        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image_url = '/uploads/' . $image_name; // Guardar la ruta de la imagen relativa
        } else {
            echo "Error al cargar la imagen.";
        }
    } else {
        $image_url = '';  // Si no hay imagen, asigna una cadena vacía
    }

    // Preparar y ejecutar la inserción en la base de datos
    $stmt = $conn->prepare("INSERT INTO books (title, author, description, category, available, publication_date, image_url, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sssssss", $title, $author, $description, $category, $available, $publication_date, $image_url);

    if ($stmt->execute()) {
        echo "Libro creado exitosamente.";
    } else {
        echo "Error al crear el libro: " . $conn->error;
    }
    $stmt->close();
}

// Lógica para editar un libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_book'])) {
    $book_id = $_POST['id'];
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $available = isset($_POST['available']) ? 1 : 0;
    $publication_date = $_POST['publication_date']; // Obtener la fecha de publicación
    $image_url = null;

    // Subir la nueva imagen si es que se ha seleccionado
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Usar una ruta absoluta para la carpeta de imágenes
        $image_dir = __DIR__ . '/../uploads/';
        $image_name = time() . "_" . $_FILES['image']['name'];
        $image_path = $image_dir . $image_name;

        // Verificar si la carpeta uploads/ existe, si no, crearla
        if (!file_exists($image_dir)) {
            mkdir($image_dir, 0777, true);  // Crear la carpeta si no existe
        }

        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image_url = '/uploads/' . $image_name; // Guardar la nueva ruta de la imagen
        } else {
            echo "Error al cargar la imagen.";
        }
    } else {
        // Si no se seleccionó una nueva imagen, mantener la imagen actual
        $stmt = $conn->prepare("SELECT image_url FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $image_url = $book['image_url']; // Mantener la imagen actual
        $stmt->close();
    }

    // Preparar y ejecutar la actualización del libro
    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, description = ?, category = ?, available = ?, publication_date = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $title, $author, $description, $category, $available, $publication_date, $image_url, $book_id);

    if ($stmt->execute()) {
        echo "Libro actualizado exitosamente.";
    } else {
        echo "Error al actualizar el libro: " . $conn->error;
    }
    $stmt->close();
}

// Lógica para borrar un libro
if (isset($_GET['delete_id'])) {
    $book_id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        echo "Libro eliminado exitosamente.";
    } else {
        echo "Error al eliminar el libro: " . $conn->error;
    }
    $stmt->close();
}
?>
