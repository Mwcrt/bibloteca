document.addEventListener("DOMContentLoaded", function() {
    const booksContainer = document.querySelector('.books-container');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const books = document.querySelectorAll('.book'); // Todos los libros disponibles
    const booksPerPage = 8; // Número de libros por página
    let currentPage = 1;

    // Función para mostrar los libros de la página actual
    function showBooksForPage(page) {
        const startIndex = (page - 1) * booksPerPage;
        const endIndex = page * booksPerPage;

        // Ocultar todos los libros
        books.forEach(book => {
            book.style.display = 'none';
        });

        // Mostrar solo los libros de la página actual
        for (let i = startIndex; i < endIndex; i++) {
            if (books[i]) {
                books[i].style.display = 'block';
            }
        }

        // Deshabilitar o habilitar los botones según sea necesario
        prevButton.disabled = page === 1; // Deshabilitar el botón "Anterior" si estamos en la primera página
        nextButton.disabled = endIndex >= books.length; // Deshabilitar el botón "Siguiente" si hemos mostrado todos los libros
    }

    // Función para manejar la paginación
    function handlePagination() {
        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                showBooksForPage(currentPage);
            }
        });

        nextButton.addEventListener('click', function() {
            if (currentPage * booksPerPage < books.length) {
                currentPage++;
                showBooksForPage(currentPage);
            }
        });
    }

    // Función para manejar el formulario de reserva
    function handleBookReservation() {
        document.querySelectorAll('.book form').forEach(form => {
            form.addEventListener('submit', function(event) {
                const confirmation = confirm("¿Estás seguro de que quieres reservar este libro?");
                if (!confirmation) {
                    event.preventDefault(); // Prevenir el envío del formulario si el usuario cancela
                }
            });
        });
    }

    // Función para manejar el hover en los libros (escalado)
function handleBookHover() {
    document.querySelectorAll('.book').forEach(book => {
        book.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.3s';
            this.style.zIndex = '1';  // Mantén el z-index bajo incluso al hacer hover
        });

        book.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = '1';  // Asegura que el z-index siga siendo bajo al quitar el hover
        });
    });
}


    // Función para manejar los detalles de cada libro
    function handleToggleDetails() {
        const toggleButtons = document.querySelectorAll('.toggle-details');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookDiv = this.closest('.book'); // Encuentra el contenedor del libro
                const description = bookDiv.querySelector('.description');
                const publicationDate = bookDiv.querySelector('.publication-date');

                // Alternar la visibilidad de la descripción y la fecha de publicación
                if (description.style.display === 'none') {
                    description.style.display = 'block';
                    publicationDate.style.display = 'block';
                    this.textContent = 'Ocultar Detalles'; // Cambiar el texto del botón a "Ocultar Detalles"
                } else {
                    description.style.display = 'none';
                    publicationDate.style.display = 'none';
                    this.textContent = 'Ver Detalles'; // Cambiar el texto del botón a "Ver Detalles"
                }
            });
        });
    }

    // Función para manejar los mensajes emergentes
    function handlePopupMessage() {
        const popupMessage = document.querySelector('.popup-message');
        const closeBtn = document.querySelector('.close-btn');

        if (popupMessage) {
            // Mostrar el popup
            popupMessage.style.display = 'flex';

            // Cerrar el popup después de 5 segundos
            setTimeout(function() {
                popupMessage.style.display = 'none';
            }, 5000); // 5 segundos

            // Funcionalidad del botón de cerrar
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    popupMessage.style.display = 'none';
                });
            }
        }
    }

    // Inicializar la visualización de libros y agregar manejadores de eventos
    function initialize() {
        showBooksForPage(currentPage);
        handlePagination();
        handleBookReservation();
        handleBookHover();
        handleToggleDetails();
        handlePopupMessage();
    }

    // Ejecutar la función de inicialización
    initialize();
});
