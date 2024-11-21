$(document).ready(function () {
    // Manejo del menú desplegable para dispositivos móviles
    const mobileScreen = window.matchMedia("(max-width: 990px)");

    $(".dashboard-nav-dropdown-toggle").click(function () {
        $(this).closest(".dashboard-nav-dropdown")
            .toggleClass("show")
            .find(".dashboard-nav-dropdown")
            .removeClass("show");
        $(this).parent()
            .siblings()
            .removeClass("show");
    });

    // Manejo del menú toggle (abrir/cerrar el menú)
    $(".menu-toggle").click(function () {
        if (mobileScreen.matches) {
            $(".dashboard-nav").toggleClass("mobile-show");
        } else {
            $(".dashboard").toggleClass("dashboard-compact");
        }
    });

    // ** Control para el modal de filtros **
    var modal = $("#filterModal");
    var filterButton = $(".dashboard-nav-dropdown-toggle");
    var closeButton = $(".close");

    // Abrir el modal cuando se haga clic en el botón de filtros
    filterButton.on("click", function() {
        modal.show();  // Usa .show() en lugar de modificar directamente el estilo
    });

    // Cerrar el modal cuando se haga clic en la "X"
    closeButton.on("click", function() {
        modal.hide();  // Usa .hide() en lugar de modificar directamente el estilo
    });

    // Cerrar el modal si se hace clic fuera de él
    $(window).on("click", function(event) {
        if ($(event.target).is(modal)) {
            modal.hide();  // Usa .hide() para cerrar el modal
        }
    });

    // ** Control para la búsqueda **
    var searchIcon = $(".search-icon");
    var searchBar = $(".search-bar");

    // Mostrar/ocultar la barra de búsqueda al hacer clic en el icono de búsqueda
    searchIcon.on("click", function() {
        searchBar.toggle();  // Usamos .toggle() para alternar la visibilidad
    });
});
