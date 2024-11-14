<link rel="stylesheet" href="../css/comentarios.css">

<?php
// Iniciar la sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../include/db.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../manages/login.html");
    exit();
}

// Conectar a la base de datos
$db = new Database();
$conn = $db->conn;
$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario desde la base de datos
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    echo "No se encontraron datos para este usuario.";
    exit();
}

// Procesar el formulario de envío de comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    if (!empty($_POST['comentario'])) {
        $comentario = $_POST['comentario'];
        $profile_picture = $user['profile_picture'];
        $username = $user['username'];

        $stmt = $conn->prepare("INSERT INTO comentarios (user_id, profile_picture, username, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $profile_picture, $username, $comentario);

        if ($stmt->execute()) {
            echo "Comentario guardado con éxito.";
        } else {
            echo "Error al guardar el comentario.";
        }
    } else {
        echo "El comentario no puede estar vacío.";
    }
}

// Procesar la acción de dar o quitar like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_comment'])) {
    $comentario_id = $_POST['comentario_id'];

    $stmt = $conn->prepare("SELECT * FROM comentarios_likes WHERE user_id = ? AND comentario_id = ?");
    $stmt->bind_param("ii", $user_id, $comentario_id);
    $stmt->execute();
    $like_result = $stmt->get_result();

    if ($like_result->num_rows == 0) {
        // Dar like
        $stmt = $conn->prepare("INSERT INTO comentarios_likes (user_id, comentario_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $comentario_id);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("UPDATE comentarios SET likes = likes + 1 WHERE id = ?");
            $stmt->bind_param("i", $comentario_id);
            $stmt->execute();
            echo "Has dado like a este comentario.";
        } else {
            echo "Hubo un error al dar like.";
        }
    } else {
        // Quitar like
        $stmt = $conn->prepare("DELETE FROM comentarios_likes WHERE user_id = ? AND comentario_id = ?");
        $stmt->bind_param("ii", $user_id, $comentario_id);
        if ($stmt->execute()) {
            $stmt = $conn->prepare("UPDATE comentarios SET likes = likes - 1 WHERE id = ?");
            $stmt->bind_param("i", $comentario_id);
            $stmt->execute();
            echo "Has quitado tu like a este comentario.";
        } else {
            echo "Hubo un error al quitar el like.";
        }
    }
}

// Procesar el formulario de envío de respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_response'])) {
    if (!empty($_POST['respuesta'])) {
        $comentario_id = $_POST['comentario_id'];
        $respuesta = $_POST['respuesta'];
        $profile_picture = $user['profile_picture'];
        $username = $user['username'];

        $stmt = $conn->prepare("INSERT INTO respuestas (user_id, comentario_id, respuesta, fecha) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $user_id, $comentario_id, $respuesta);

        if ($stmt->execute()) {
            echo "Respuesta guardada con éxito.";
        } else {
            echo "Error al guardar la respuesta.";
        }
    } else {
        echo "La respuesta no puede estar vacía.";
    }
}

// Verificar si la solicitud es AJAX
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Si no es una solicitud AJAX, mostrar la estructura completa
if (!$is_ajax) {
    echo '<link rel="stylesheet" href="../css/comentarios.css">';
}
?>

<div class="comments-container" id="comments-container">
    <?php
    // Si se recibe la solicitud AJAX para refrescar los comentarios, se inicializa la página en 1
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $comments_per_page = 5;
    $offset = ($page - 1) * $comments_per_page;

    // Obtener y mostrar comentarios existentes
    $stmt = $conn->prepare("SELECT id, profile_picture, username, comentario, fecha, likes FROM comentarios ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $comments_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Comentario principal
            echo "<div class='comentario' id='comentario-" . $row['id'] . "'>";

            // Cabecera del comentario
            echo "<div class='comentario-header'>";
            echo "<img src='../uploads/profile_pictures/" . htmlspecialchars($row['profile_picture']) . "' alt='Foto de perfil'>";
            echo "<div class='comentario-content'>";
            echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['comentario']) . " <br><small>" . htmlspecialchars($row['fecha']) . "</small></p>";
            echo "<p>Likes: " . htmlspecialchars($row['likes']) . "</p>";

            // Botones de acción para dar like y responder
            echo "<div class='comentarios-actions'>";

            // Verificar si el usuario ya ha dado like
            $stmt_likes = $conn->prepare("SELECT * FROM comentarios_likes WHERE user_id = ? AND comentario_id = ?");
            $stmt_likes->bind_param("ii", $user_id, $row['id']);
            $stmt_likes->execute();
            $like_result = $stmt_likes->get_result();
            $liked = $like_result->num_rows > 0;

            // Botón de like
            echo "<form method='POST' action=''>
                    <input type='hidden' name='comentario_id' value='" . $row['id'] . "'>
                    <button type='submit' name='like_comment' class='like-button " . ($liked ? 'liked' : '') . "'>
                        <i class='" . ($liked ? 'fas' : 'far') . " fa-heart'></i> Like
                    </button>
                  </form>";

            // Botón de responder
            echo "<button class='btn-responder' data-comentario-id='" . $row['id'] . "'>
                    <i class='fas fa-reply'></i> Responder
                  </button>";

            // Botón de ver respuestas
            echo "<button class='btn-ver-respuestas' data-comentario-id='" . $row['id'] . "'>
                    <i class='fas fa-comment-dots'></i> Ver Respuestas
                  </button>";

            echo "</div></div></div>"; // Cerrar comentario-header y comentario-content
            echo "</div>"; // Cerrar comentario principal

            // Contenedor para las respuestas, debajo del comentario principal
            echo "<div class='respuesta-container' id='respuestas-" . $row['id'] . "' style='display:none;'>";

            // Obtener respuestas del comentario actual
            $stmt_respuestas = $conn->prepare("SELECT r.respuesta, r.fecha, u.username, u.profile_picture FROM respuestas r INNER JOIN users u ON r.user_id = u.id WHERE r.comentario_id = ? ORDER BY r.fecha DESC");
            $stmt_respuestas->bind_param("i", $row['id']);
            $stmt_respuestas->execute();
            $respuestas_result = $stmt_respuestas->get_result();

            if ($respuestas_result->num_rows > 0) {
                while ($respuesta = $respuestas_result->fetch_assoc()) {
                    // Respuesta individual
                    echo "<div class='respuesta'>";
                    echo "<img src='../uploads/profile_pictures/" . htmlspecialchars($respuesta['profile_picture']) . "' alt='Foto de perfil' class='respuesta-img'>";
                    echo "<p><strong>" . htmlspecialchars($respuesta['username']) . ":</strong> " . htmlspecialchars($respuesta['respuesta']) . " <br><small>" . htmlspecialchars($respuesta['fecha']) . "</small></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay respuestas aún.</p>";
            }
            echo "</div>"; // Cerrar div de respuesta-container

            // Formulario para agregar una nueva respuesta
            echo "<form id='form-respuesta-" . $row['id'] . "' method='POST' action='' style='display:none;'>
                    <input type='hidden' name='comentario_id' value='" . $row['id'] . "'>
                    <textarea name='respuesta' placeholder='Escribe tu respuesta...'></textarea>
                    <button type='submit' name='submit_response'>Responder</button>
                  </form>";
        }
    } else {
        echo "<p>No hay comentarios aún.</p>";
    }

    // Verificar si hay más comentarios disponibles
    $stmt = $conn->prepare("SELECT COUNT(id) FROM comentarios");
    $stmt->execute();
    $count_result = $stmt->get_result();
    $total_comments = $count_result->fetch_row()[0];

    $total_pages = ceil($total_comments / $comments_per_page);
    if ($page < $total_pages) {
        echo "<button id='load-more-comments' data-page='" . ($page + 1) . "'>Cargar más comentarios</button>";
    } else {
        echo "<button id='load-more-comments' data-page='1'>Refrescar</button>";
    }

    // Añadir el total de comentarios en un campo oculto
    echo "<input type='hidden' id='total-comments' value='" . $total_comments . "'>";

    $stmt->close();
    $conn->close();
    ?>
</div>

<script src="../js/comentarios.js"></script>
