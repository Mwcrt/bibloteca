<?php include '../manages/reservation.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="../css/reservation.css"> <!-- Vincula el archivo CSS externo -->
</head>
<body>

<div class="container"> <!-- Div contenedor principal -->

    <?php include '../include/menubar.php'; ?>  <!-- Incluir el menú -->

    <h1>Mis Reservas</h1>

    <!-- Mostrar mensaje de sesión si existe -->
    <?php if (isset($_SESSION['message'])) { ?>
        <p><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <h2>Libros Reservados</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>ID Reserva</th>
                <th>Título del Libro</th>
                <th>Autor</th>
                <th>Fecha de Publicación</th>
                <th>Fecha de Reserva</th>
                <th>Fecha de Expiración</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($reservation = $reservations_result->fetch_assoc()) { 
                // Formatear las fechas
                $reservation_date = date('d/m/Y', strtotime($reservation['reservation_date']));
                $expiration_date = date('d/m/Y', strtotime($reservation['expiration_date']));
                $loan_date = $reservation['loan_date'] ? date('d/m/Y', strtotime($reservation['loan_date'])) : 'No prestado';
                $return_date = $reservation['return_date'] ? date('d/m/Y', strtotime($reservation['return_date'])) : 'No devuelto';
            ?>
                <tr>
                    <td>
                        <?php
                        if ($reservation['image_url']) {
                            $image_path = 'uploads/' . basename($reservation['image_url']);
                            echo "<img src='/bibloteca/" . $image_path . "' alt='Imagen del libro' width='100'>";
                        } else {
                            echo "Sin imagen";
                        }
                        ?>
                    </td>
                    <td><?php echo $reservation['id']; ?></td>
                    <td><?php echo $reservation['title']; ?></td>
                    <td><?php echo $reservation['author']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($reservation['publication_date'])); ?></td>
                    <td><?php echo $reservation_date; ?></td>
                    <td><?php echo $expiration_date; ?></td>
                    <td><?php echo $loan_date; ?></td>
                    <td><?php echo $return_date; ?></td>
                    <td>
                        <form action="reservation_form.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres cancelar la reserva?');">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="cancel">Cancelar Reserva</button>
                        </form>

                        <?php if (!$reservation['loan_date'] && strtotime($reservation['expiration_date']) > time()) { ?>
                            <form action="reservation_form.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres realizar el préstamo?');">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <input type="hidden" name="action" value="loan">
                                
                                <label for="loan_duration">Duración del préstamo:</label>
                                    <select name="loan_duration" required>
                                        <option value="7">7 días</option>
                                        <option value="14">14 días</option>
                                        <option value="30">30 días</option>
                                    </select>
                                    <button type="submit" class="loan">Realizar Préstamo</button>
                            </form>
                        <?php } elseif ($reservation['loan_date'] && !$reservation['return_date']) { ?>
                            <form action="reservation_form.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres devolver el libro?');">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <input type="hidden" name="action" value="return">
                                <button type="submit" class="return">Devolver Libro</button>
                            </form>
                        <?php } else { ?>
                            <p>Libro devuelto</p>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div> <!-- Fin del div contenedor principal -->

</body>
</html>


