<?php session_start();?>
<?php include '../include/menubar.php'; ?> 

<link rel="stylesheet" href="../css/comentarios.css">
<div class="main-container">
    
    <div class="comentarios-header">
        <h1>Comentarios</h1>
        <h2>Aquí puedes socializar y compartir ideas con los demás! 💕</h2>

        
        <form action="comentarios_form.php" method="POST" id="comentarioForm">
            <textarea name="comentario" placeholder="Escribe tu comentario" required maxlength="500" minlength="5"></textarea><br>
            <button type="submit" name="submit_comment" id="Enviar">Publicar</button>
        </form>
    </div>


    <div id="comentarios-container">
        <div id="comentarios">
            <?php
            
            include '../manages/comentarios.php'; 
            ?>
        </div>
    </div>
</div>