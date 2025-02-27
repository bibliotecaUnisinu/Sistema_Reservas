// Control del menú lateral
const toggleMenu = document.getElementById('toggle-menu');
const sidebar = document.getElementById('sidebar');
const closeMenu = document.getElementById('close-menu');

// Función para abrir o cerrar el menú lateral
toggleMenu.addEventListener('click', function () {
    sidebar.classList.toggle('show'); // Alternar la clase 'show' para mostrar/ocultar el menú
});

// Cerrar el menú lateral al hacer clic en el botón de cerrar
closeMenu.addEventListener('click', function () {
    sidebar.classList.remove('show'); // Eliminar la clase 'show' para ocultar el menú
});

// Cerrar el menú lateral al hacer clic fuera de él
document.addEventListener('click', function (event) {
    if (sidebar.classList.contains('show') && !sidebar.contains(event.target) && event.target !== toggleMenu) {
        sidebar.classList.remove('show'); // Ocultar el menú si se hace clic fuera de él
    }
});