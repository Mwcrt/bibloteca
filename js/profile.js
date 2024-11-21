// Esperar que el documento esté listo
document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("toggleProfile");
    const editSections = document.getElementById("editSections");

    // Al hacer clic en el botón "Editar Mi Perfil"
    toggleButton.addEventListener("click", function () {
        // Mostrar/ocultar las secciones
        if (editSections.style.display === "none") {
            editSections.style.display = "block";
            toggleButton.textContent = "Ocultar Secciones";
        } else {
            editSections.style.display = "none";
            toggleButton.textContent = "Editar Mi Perfil";
        }
    });
});
