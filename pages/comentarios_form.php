<?php session_start();?>
<?php include '../include/menubar.php'; ?> 

<link rel="stylesheet" href="../css/comentarios.css">
<div class="main-container">
    
    <!-- Contenedor con fondo transparente para el encabezado y formulario -->
    <div class="comentarios-header">
        <h1>Comentarios</h1>
        <h2>Aquí puedes socializar y compartir ideas con los demás! 💕</h2>

        <!-- Formulario de comentario -->
        <form action="comentarios_form.php" method="POST" id="comentarioForm">
            <textarea name="comentario" placeholder="Escribe tu comentario" required maxlength="500" minlength="5"></textarea><br>
            <button type="submit" name="submit_comment" id="Enviar">Publicar</button>
        </form>
    </div>

    <!-- Sección donde se muestran los comentarios -->
    <div id="comentarios-container">
        <div id="comentarios">
            <?php
            // Incluir el archivo PHP que procesa los comentarios y los muestra
            include '../manages/comentarios.php'; 
            ?>
        </div>
    </div>
</div>