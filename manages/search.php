<?php
session_start();
include '../include/db.php';  // Incluir la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../manages/login.html");
    exit();
}

$db = new Database();
$conn = $db->conn;
$user_id = $_SESSION['user_id'];

// Lógica para realizar una reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Verificar si el libro ya ha sido reservado por el mismo usuario
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si no hay ninguna reserva existente, agregar la nueva reserva
    if ($result->num_rows == 0) {
        // Obtener el estado de disponibilidad del libro (0 = no disponible, 1 = reservado, 3 = disponible)
        $stmt = $conn->prepare("SELECT available FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $book_result = $stmt->get_result();
        
        if ($book_result->num_rows > 0) {
            $book = $book_result->fetch_assoc();
            $availability_status = $book['available'];
            
            // Verificar si el libro está disponible (available = 3)
            if ($availability_status == 3) {
                // Obtener la fecha actual para la reserva
                $reservation_date = date('Y-m-d');
                
                // Calcular la fecha de expiración (7 días después)
                $expiration_date = date('Y-m-d', strtotime('+7 days'));
                
                // Obtener la fecha de publicación del libro
                $publication_date = $book['publication_date'];

                // Marcar el libro como reservado (available = 1)
                $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE id = ?");
                $stmt->bind_param("i", $book_id);
                if ($stmt->execute()) {
                    // Insertar la nueva reserva
                    $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, reservation_date, expiration_date, publication_date) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisss", $user_id, $book_id, $reservation_date, $expiration_date, $publication_date);
                    $stmt->execute();
                    
                    $_SESSION['message'] = ["success", "Libro reservado con éxito.✔️"];
                } else {
                    $_SESSION['message'] = ["error", "❌ Hubo un error al actualizar la disponibilidad del libro."];
                }
            } elseif ($availability_status == 0) {
                $_SESSION['message'] = ["warning", "❌ El libro no está disponible en este momento."];
            } elseif ($availability_status == 1) {
                $_SESSION['message'] = ["warning", "⚠️ El libro ya está reservado por otro usuario."];
            }
        } else {
            $_SESSION['message'] = ["error", "❌ No se encontró el libro para realizar la reserva."];
        }
    } else {
        $_SESSION['message'] = ["warning", "Ya has reservado este libro.⚠️"];
    }
    $stmt->close();
}

// Obtener parámetros de búsqueda
$search_query = "";
$category_filter = "";
$year_filter = "";  // Filtro por año
$order = "ASC";  // Orden por defecto de A-Z

// Comprobar si hay una consulta de búsqueda
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = "%" . $conn->real_escape_string($_GET['query']) . "%";
}

// Comprobar si se ha seleccionado una categoría
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_filter = $conn->real_escape_string($_GET['category']);
}

// Comprobar si se ha seleccionado un año
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year_filter = $_GET['year'];
}

// Comprobar el orden seleccionado (A-Z o Z-A)
if (isset($_GET['order']) && $_GET['order'] === 'DESC') {
    $order = "DESC";
}

// Obtener los años únicos de los libros para el filtro
$years_sql = "SELECT DISTINCT YEAR(publication_date) AS year FROM books ORDER BY year DESC";
$years_result = $conn->query($years_sql);

// Obtener el total de libros disponibles
$total_books_sql = "SELECT COUNT(*) AS total_books FROM books WHERE 1=1";  // "1=1" para facilitar la concatenación de condiciones
if (!empty($search_query)) {
    $total_books_sql .= " AND (title LIKE ? OR author LIKE ?)";
}
if (!empty($category_filter)) {
    $total_books_sql .= " AND category = ?";
}
if (!empty($year_filter)) {
    $total_books_sql .= " AND YEAR(publication_date) = ?";
}

$total_books_stmt = $conn->prepare($total_books_sql);
if (!empty($search_query) && !empty($category_filter) && !empty($year_filter)) {
    $total_books_stmt->bind_param("ssss", $search_query, $search_query, $category_filter, $year_filter);
} elseif (!empty($search_query) && !empty($category_filter)) {
    $total_books_stmt->bind_param("sss", $search_query, $search_query, $category_filter);
} elseif (!empty($search_query) && !empty($year_filter)) {
    $total_books_stmt->bind_param("sss", $search_query, $search_query, $year_filter);
} elseif (!empty($category_filter) && !empty($year_filter)) {
    $total_books_stmt->bind_param("ss", $category_filter, $year_filter);
} elseif (!empty($search_query)) {
    $total_books_stmt->bind_param("ss", $search_query, $search_query);
} elseif (!empty($category_filter)) {
    $total_books_stmt->bind_param("s", $category_filter);
} elseif (!empty($year_filter)) {
    $total_books_stmt->bind_param("s", $year_filter);
}

$total_books_stmt->execute();
$total_books_result = $total_books_stmt->get_result();
$total_books_stmt->close();
$total_books_row = $total_books_result->fetch_assoc();
$total_books = $total_books_row['total_books'];

// Construir la consulta SQL dinámica según los filtros
$sql = "SELECT * FROM books WHERE 1=1";  // "1=1" es un truco para simplificar concatenar condiciones

// Si hay una búsqueda, filtrar por título o autor
if (!empty($search_query)) {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
}

// Si hay una categoría seleccionada, filtrar por categoría
if (!empty($category_filter)) {
    $sql .= " AND category = ?";
}

// Si hay un filtro de año seleccionado
if (!empty($year_filter)) {
    $sql .= " AND YEAR(publication_date) = ?";
}

// Agregar el orden (A-Z o Z-A) por título
$sql .= " ORDER BY title $order";

// Preparar la consulta con los parámetros adecuados
$stmt = $conn->prepare($sql);

// Vincular los parámetros según los filtros seleccionados
if (!empty($search_query) && !empty($category_filter) && !empty($year_filter)) {
    $stmt->bind_param("ssss", $search_query, $search_query, $category_filter, $year_filter);
} elseif (!empty($search_query) && !empty($category_filter)) {
    $stmt->bind_param("sss", $search_query, $search_query, $category_filter);
} elseif (!empty($search_query) && !empty($year_filter)) {
    $stmt->bind_param("sss", $search_query, $search_query, $year_filter);
} elseif (!empty($category_filter) && !empty($year_filter)) {
    $stmt->bind_param("ss", $category_filter, $year_filter);
} elseif (!empty($search_query)) {
    $stmt->bind_param("ss", $search_query, $search_query);
} elseif (!empty($category_filter)) {
    $stmt->bind_param("s", $category_filter);
} elseif (!empty($year_filter)) {
    $stmt->bind_param("s", $year_filter);
}

// Ejecutar la consulta
$stmt->execute();
$books_result = $stmt->get_result();
$stmt->close();
?>
