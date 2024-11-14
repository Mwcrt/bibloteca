document.addEventListener("DOMContentLoaded", function() {
    // Función para cargar los comentarios
    function loadComments(page) {
        fetch('../manages/comentarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `page=${page}`
        })
        .then(response => response.text())
        .then(data => {
            // Actualizar el contenedor de comentarios
            document.getElementById('comments-container').innerHTML = data;

            // Reactivar el evento de clic para el botón "Cargar más"
            attachLoadMoreButtonEvent();
        })
        .catch(error => {
            console.error('Error al cargar los comentarios:', error);
        });
    }

    // Función para asignar el evento al botón "Cargar más"
    function attachLoadMoreButtonEvent() {
        const loadMoreButton = document.getElementById('load-more-comments');
        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                loadComments(page);
            });
        }
    }

    function attachLoadMoreButtonEvent() {
        const loadMoreButton = document.getElementById('load-more-comments');
        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                loadComments(page);
            });
        }
    }

    // Asignar eventos a los botones "Responder" y "Ver respuestas"
    function assignCommentEvents() {
        // Delegación de eventos para "Ver respuestas"
        const commentsContainer = document.getElementById('comments-container');
        commentsContainer.addEventListener('click', function(event) {
            // Ver respuestas
            if (event.target && event.target.matches('.btn-ver-respuestas')) {
                const comentarioId = event.target.getAttribute('data-comentario-id');
                const respuestasDiv = document.getElementById(`respuestas-${comentarioId}`);
                respuestasDiv.style.display = respuestasDiv.style.display === 'none' ? 'block' : 'none';
            }

            // Responder
            if (event.target && event.target.matches('.btn-responder')) {
                const comentarioId = event.target.getAttribute('data-comentario-id');
                const form = document.getElementById(`form-respuesta-${comentarioId}`);
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar respuestas
        document.querySelectorAll('.btn-ver-respuestas').forEach(button => {
            button.addEventListener('click', function() {
                const comentarioId = button.getAttribute('data-comentario-id');
                const respuestasDiv = document.getElementById(`respuestas-${comentarioId}`);
                respuestasDiv.style.display = respuestasDiv.style.display === 'none' ? 'block' : 'none';
            });
        });
    
        // Mostrar formulario para responder
        document.querySelectorAll('.btn-responder').forEach(button => {
            button.addEventListener('click', function() {
                const comentarioId = button.getAttribute('data-comentario-id');
                const form = document.getElementById(`form-respuesta-${comentarioId}`);
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            });
        });
    });
    


    assignCommentEvents();
    attachLoadMoreButtonEvent();
});

