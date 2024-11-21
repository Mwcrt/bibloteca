<?php
session_start();
include '../include/db.php';

class BookManager {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conn;
    }

    private function uploadImage($image) {
        if (isset($image) && $image['error'] == 0) {
            $image_dir = __DIR__ . '/../uploads/';
            if (!file_exists($image_dir)) {
                mkdir($image_dir, 0777, true);
            }
            $image_name = time() . "_" . $image['name'];
            $image_path = $image_dir . $image_name;

            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                return '/uploads/' . $image_name;
            } else {
                throw new Exception("Error al cargar la imagen.");
            }
        }
        return '';
    }

    public function createBook($title, $author, $description, $category, $available, $publication_date, $image) {
        $image_url = $this->uploadImage($image);
        
        $stmt = $this->conn->prepare("INSERT INTO books (title, author, description, category, available, publication_date, image_url, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->bind_param("ssssiss", $title, $author, $description, $category, $available, $publication_date, $image_url);

        if (!$stmt->execute()) {
            throw new Exception("Error al crear el libro: " . $this->conn->error);
        }
        echo "Libro creado exitosamente.";
    }

    public function updateBook($book_id, $title, $author, $description, $category, $available, $publication_date, $image) {
        $image_url = $this->uploadImage($image);

        // Mantener la imagen actual si no se ha seleccionado una nueva
        if (empty($image_url)) {
            $stmt = $this->conn->prepare("SELECT image_url FROM books WHERE id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book = $result->fetch_assoc();
            $image_url = $book['image_url'];
            $stmt->close();
        }

        $stmt = $this->conn->prepare("UPDATE books SET title = ?, author = ?, description = ?, category = ?, available = ?, publication_date = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssssissi", $title, $author, $description, $category, $available, $publication_date, $image_url, $book_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el libro: " . $this->conn->error);
        }
        echo "Libro actualizado exitosamente.";
    }

    public function deleteBook($book_id) {
        $stmt = $this->conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el libro: " . $this->conn->error);
        }
        echo "Libro eliminado exitosamente.";
    }
}

// Verificar que el usuario esté autenticado y sea administrador
function checkAdminAccess() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
        exit();
    }
}

checkAdminAccess();

$bookManager = new BookManager();

// Crear libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_book'])) {
    try {
        $bookManager->createBook(
            trim($_POST['title']),
            trim($_POST['author']),
            trim($_POST['description']),
            trim($_POST['category']),
            isset($_POST['available']) ? 1 : 0,
            $_POST['publication_date'],
            $_FILES['image']
        );
    } catch (Exception $e) {
        echo "<p style='color:red;'>".$e->getMessage()."</p>";
    }
}

// Editar libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_book'])) {
    try {
        $bookManager->updateBook(
            $_POST['id'],
            trim($_POST['title']),
            trim($_POST['author']),
            trim($_POST['description']),
            trim($_POST['category']),
            isset($_POST['available']) ? 1 : 0,
            $_POST['publication_date'],
            $_FILES['image']
        );
    } catch (Exception $e) {
        echo "<p style='color:red;'>".$e->getMessage()."</p>";
    }
}

// Borrar libro
if (isset($_GET['delete_id'])) {
    try {
        $bookManager->deleteBook($_GET['delete_id']);
    } catch (Exception $e) {
        echo "<p style='color:red;'>".$e->getMessage()."</p>";
    }
}

echo "Bienvenido al panel de administración, " . $_SESSION['username'] . "!<br>";
echo "<a href='../manages/logout.php'>Cerrar sesión</a>";
?>
