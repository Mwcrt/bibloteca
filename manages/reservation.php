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

// Mostrar mensaje de sesión si existe
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);  // Limpiar el mensaje después de mostrarlo
}

// Función para cancelar la reserva
function cancelReservation($reservation_id, $user_id, $conn) {
    // Verificar que la reserva pertenece al usuario
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reservation_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // La reserva existe y pertenece al usuario, proceder a eliminarla
        $stmt = $conn->prepare("SELECT book_id FROM reservations WHERE id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $book_id = $book['book_id'];  // Obtener el ID del libro relacionado

        // Eliminar la reserva
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->bind_param("i", $reservation_id);

        if ($stmt->execute()) {
            // Actualizar la disponibilidad del libro a 3 (disponible)
            $stmt = $conn->prepare("UPDATE books SET available = 3 WHERE id = ?");
            $stmt->bind_param("i", $book_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Reserva cancelada con éxito y el libro está disponible nuevamente.";
            } else {
                $_SESSION['message'] = "Error al actualizar la disponibilidad del libro.";
            }
        } else {
            $_SESSION['message'] = "Error al cancelar la reserva.";
        }
    } else {
        $_SESSION['message'] = "No se encontró la reserva o no tienes permiso para cancelarla.";
    }

    $stmt->close();
}

// Función para realizar el préstamo
function loanBook($reservation_id, $loan_duration, $conn) {
    // Verificar que la reserva existe y no ha sido prestada todavía
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND loan_date IS NULL");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Calcular las fechas de préstamo y expiración
        $loan_date = date('Y-m-d'); // Fecha actual
        $expiration_date = date('Y-m-d', strtotime("+$loan_duration days")); // Fecha de expiración

        // Actualizar la reserva con las fechas de préstamo y expiración
        $stmt = $conn->prepare("UPDATE reservations SET loan_date = ?, expiration_date = ? WHERE id = ?");
        $stmt->bind_param("ssi", $loan_date, $expiration_date, $reservation_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "El libro ha sido prestado por $loan_duration días.";
        } else {
            $_SESSION['message'] = "Error al realizar el préstamo.";
        }
    } else {
        $_SESSION['message'] = "La reserva no existe o ya ha sido prestada.";
    }

    $stmt->close();
}

// Lógica para cancelar la reserva (cuando se envía el formulario de cancelación)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id']) && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $reservation_id = $_POST['reservation_id'];
    cancelReservation($reservation_id, $user_id, $conn);
    
}

// Lógica para realizar el préstamo (cuando se envía el formulario de préstamo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id']) && isset($_POST['action']) && $_POST['action'] === 'loan') {
    $reservation_id = $_POST['reservation_id'];
    $loan_duration = $_POST['loan_duration']; // Duración seleccionada en días
    loanBook($reservation_id, $loan_duration, $conn);
}

// Lógica para devolver el libro y actualizar la disponibilidad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id']) && isset($_POST['action']) && $_POST['action'] === 'return') {
    $reservation_id = $_POST['reservation_id'];
    $return_date = date('Y-m-d'); // Fecha actual como fecha de devolución

    // Iniciar transacción para asegurar consistencia en la base de datos
    $conn->begin_transaction();

    // 1. Actualizar la fecha de devolución en la tabla reservations
    $stmt = $conn->prepare("UPDATE reservations SET return_date = ? WHERE id = ?");
    $stmt->bind_param("si", $return_date, $reservation_id);

    if ($stmt->execute()) {
        // 2. Obtener el ID del libro relacionado con la reserva
        $stmt = $conn->prepare("SELECT book_id FROM reservations WHERE id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $book_id = $book['book_id'];

        // 3. Actualizar el estado de disponibilidad del libro en la tabla books
        $stmt = $conn->prepare("UPDATE books SET available = 3 WHERE id = ?");
        $stmt->bind_param("i", $book_id);

        if ($stmt->execute()) {
            // Si ambas consultas fueron exitosas, confirmamos la transacción
            $conn->commit();
            $_SESSION['message'] = "El libro ha sido devuelto correctamente y está disponible nuevamente.";
        } else {
            // Si la segunda consulta falla, deshacer la transacción
            $conn->rollback();
            $_SESSION['message'] = "Error al actualizar la disponibilidad del libro.";
        }
    } else {
        // Si la primera consulta falla, deshacer la transacción
        $conn->rollback();
        $_SESSION['message'] = "Error al devolver el libro.";
    }

    $stmt->close();
}

// Obtener las reservas del usuario (con fecha de publicación y la imagen del libro)
$reservations_stmt = $conn->prepare("SELECT reservations.id, books.title, books.author, books.publication_date, 
                                      reservations.reservation_date, reservations.expiration_date, reservations.loan_date, 
                                      reservations.return_date, books.image_url
                                      FROM reservations
                                      JOIN books ON reservations.book_id = books.id
                                      WHERE reservations.user_id = ?");
$reservations_stmt->bind_param("i", $user_id);
$reservations_stmt->execute();
$reservations_result = $reservations_stmt->get_result();

$conn->close();
?>
