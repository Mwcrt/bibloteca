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

    // Verificar si el libro ya ha sido reservado
    if (isBookReserved($conn, $user_id, $book_id)) {
        $_SESSION['message'] = ["warning", "Ya has reservado este libro.⚠️"];
    } else {
        handleReservation($conn, $user_id, $book_id);
    }
}

// Obtener los parámetros de búsqueda y filtro
$filters = getSearchFilters();
$years_result = getUniquePublicationYears($conn);
$total_books = getTotalBooksCount($conn, $filters);
$books_result = getFilteredBooks($conn, $filters);

// Función para verificar si un libro ya ha sido reservado
function isBookReserved($conn, $user_id, $book_id) {
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    return $result->num_rows > 0;
}

// Función para manejar la lógica de reserva
function handleReservation($conn, $user_id, $book_id) {
    // Obtener la disponibilidad del libro
    $availability_status = getBookAvailabilityStatus($conn, $book_id);

    switch ($availability_status) {
        case 3: // Disponible
            $reservation_date = date('Y-m-d');
            $expiration_date = date('Y-m-d', strtotime('+7 days'));
            $publication_date = getBookPublicationDate($conn, $book_id);
            if (reserveBook($conn, $book_id)) {
                addReservation($conn, $user_id, $book_id, $reservation_date, $expiration_date, $publication_date);
                $_SESSION['message'] = ["success", "Libro reservado con éxito.✔️"];
            } else {
                $_SESSION['message'] = ["error", "❌ Hubo un error al actualizar la disponibilidad del libro."];
            }
            break;

        case 0: // No disponible
            $_SESSION['message'] = ["warning", "❌ El libro no está disponible en este momento."];
            break;

        case 1: // Ya reservado
            $_SESSION['message'] = ["warning", "⚠️ El libro ya está reservado por otro usuario."];
            break;
    }
}

// Función para obtener la disponibilidad del libro
function getBookAvailabilityStatus($conn, $book_id) {
    $stmt = $conn->prepare("SELECT available FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book_result = $stmt->get_result();
    $stmt->close();

    if ($book_result->num_rows > 0) {
        $book = $book_result->fetch_assoc();
        return $book['available'];
    }

    return null;
}

// Función para obtener la fecha de publicación de un libro
function getBookPublicationDate($conn, $book_id) {
    $stmt = $conn->prepare("SELECT publication_date FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book_result = $stmt->get_result();
    $stmt->close();
    
    if ($book_result->num_rows > 0) {
        $book = $book_result->fetch_assoc();
        return $book['publication_date'];
    }

    return null;
}

// Función para reservar el libro
function reserveBook($conn, $book_id) {
    $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Función para agregar la reserva en la base de datos
function addReservation($conn, $user_id, $book_id, $reservation_date, $expiration_date, $publication_date) {
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, reservation_date, expiration_date, publication_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $book_id, $reservation_date, $expiration_date, $publication_date);
    $stmt->execute();
    $stmt->close();
}

// Función para obtener los filtros de búsqueda
function getSearchFilters() {
    $filters = [
        'search_query' => '',
        'category_filter' => '',
        'year_filter' => '',
        'order' => 'ASC'
    ];

    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $filters['search_query'] = "%" . $GLOBALS['conn']->real_escape_string($_GET['query']) . "%";
    }

    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $filters['category_filter'] = $GLOBALS['conn']->real_escape_string($_GET['category']);
    }

    if (isset($_GET['year']) && !empty($_GET['year'])) {
        $filters['year_filter'] = $_GET['year'];
    }

    if (isset($_GET['order']) && $_GET['order'] === 'DESC') {
        $filters['order'] = 'DESC';
    }

    return $filters;
}

// Función para obtener los años únicos de los libros
function getUniquePublicationYears($conn) {
    $years_sql = "SELECT DISTINCT YEAR(publication_date) AS year FROM books ORDER BY year DESC";
    return $conn->query($years_sql);
}

// Función para obtener el total de libros según los filtros
function getTotalBooksCount($conn, $filters) {
    $total_books_sql = "SELECT COUNT(*) AS total_books FROM books WHERE 1=1";  // "1=1" es para facilitar concatenar condiciones

    if (!empty($filters['search_query'])) {
        $total_books_sql .= " AND (title LIKE ? OR author LIKE ?)";
    }
    if (!empty($filters['category_filter'])) {
        $total_books_sql .= " AND category = ?";
    }
    if (!empty($filters['year_filter'])) {
        $total_books_sql .= " AND YEAR(publication_date) = ?";
    }

    $total_books_stmt = $conn->prepare($total_books_sql);

    // Vincular los parámetros según los filtros seleccionados
    bindFiltersParams($total_books_stmt, $filters);

    $total_books_stmt->execute();
    $total_books_result = $total_books_stmt->get_result();
    $total_books_stmt->close();

    $total_books_row = $total_books_result->fetch_assoc();
    return $total_books_row['total_books'];
}

// Función para construir y ejecutar la consulta de libros filtrados
function getFilteredBooks($conn, $filters) {
    $sql = "SELECT * FROM books WHERE 1=1";

    if (!empty($filters['search_query'])) {
        $sql .= " AND (title LIKE ? OR author LIKE ?)";
    }
    if (!empty($filters['category_filter'])) {
        $sql .= " AND category = ?";
    }
    if (!empty($filters['year_filter'])) {
        $sql .= " AND YEAR(publication_date) = ?";
    }

    $sql .= " ORDER BY title " . $filters['order'];

    $stmt = $conn->prepare($sql);
    bindFiltersParams($stmt, $filters);

    $stmt->execute();
    $books_result = $stmt->get_result();
    $stmt->close();
    
    return $books_result;
}

// Función para vincular parámetros a la consulta
function bindFiltersParams($stmt, $filters) {
    if (!empty($filters['search_query']) && !empty($filters['category_filter']) && !empty($filters['year_filter'])) {
        $stmt->bind_param("ssss", $filters['search_query'], $filters['search_query'], $filters['category_filter'], $filters['year_filter']);
    } elseif (!empty($filters['search_query']) && !empty($filters['category_filter'])) {
        $stmt->bind_param("sss", $filters['search_query'], $filters['search_query'], $filters['category_filter']);
    } elseif (!empty($filters['search_query']) && !empty($filters['year_filter'])) {
        $stmt->bind_param("sss", $filters['search_query'], $filters['search_query'], $filters['year_filter']);
    } elseif (!empty($filters['category_filter']) && !empty($filters['year_filter'])) {
        $stmt->bind_param("ss", $filters['category_filter'], $filters['year_filter']);
    } elseif (!empty($filters['search_query'])) {
        $stmt->bind_param("ss", $filters['search_query'], $filters['search_query']);
    } elseif (!empty($filters['category_filter'])) {
        $stmt->bind_param("s", $filters['category_filter']);
    } elseif (!empty($filters['year_filter'])) {
        $stmt->bind_param("s", $filters['year_filter']);
    }
}
?>

<?php
