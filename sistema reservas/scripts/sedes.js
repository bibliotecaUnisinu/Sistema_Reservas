// Variables globales para almacenar la información de la sede seleccionada
let selectedSede = {}; // Objeto para almacenar la información de la sede seleccionada

// Función para abrir el modal con los detalles de la sede
function openModal(name, address, contact, id, state) {
    // Asignar los valores a los elementos del modal
    document.getElementById("modal-name").textContent = name;
    document.getElementById("modal-address").textContent = address;
    document.getElementById("modal-contact").textContent = contact;

    // Almacenar la información de la sede seleccionada
    selectedSede = { id, name, address, contact, state };

    // Mostrar el modal
    document.getElementById("sedeModal").style.display = "block";
}

// Función para cerrar el modal de detalles
function closeModal() {
    document.getElementById("sedeModal").style.display = "none"; // Ocultar el modal
}

// Función para abrir el modal de edición con los datos actuales de la sede
function openEditModal() {
    closeModal(); // Cerrar el modal de detalles
    // Asignar los valores actuales de la sede a los campos del modal de edición
    document.getElementById("edit-id").value = selectedSede.id;
    document.getElementById("edit-nombre").value = selectedSede.name;
    document.getElementById("edit-direccion").value = selectedSede.address;
    document.getElementById("edit-contacto").value = selectedSede.contact;
    document.getElementById("edit-state").checked = selectedSede.state == 1; // Marcar el estado si es 1
    document.getElementById("editModal").style.display = "block"; // Mostrar el modal de edición
}

// Función para cerrar el modal de edición
function closeEditModal() {
    document.getElementById("editModal").style.display = "none"; // Ocultar el modal de edición
}

// Mostrar el modal de confirmación al intentar enviar el formulario de edición
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío del formulario
    document.getElementById('modalConfirmacion').style.display = 'block'; // Mostrar el modal de confirmación
});

// Manejar el clic en el botón Aceptar del modal de confirmación
document.getElementById('btnAceptar').onclick = function() {
    document.querySelector('form').submit(); // Enviar el formulario
};

// Manejar el clic en el botón Cancelar del modal de confirmación
document.getElementById('btnCancelar').onclick = function() {
    document.getElementById('modalConfirmacion').style.display = 'none'; // Ocultar el modal de confirmación
};

// Función para alternar visualmente la sede y sus espacios
function toggleSedeVisibility(sedeId) {
    const sedeCard = document.querySelector(`.sede-card[data-id="${sedeId}"]`); // Obtener la tarjeta de la sede
    const relatedSpaces = document.querySelectorAll(`.sede-card[data-sede-id="${sedeId}"]`); // Obtener los espacios relacionados

    sedeCard.classList.toggle('oculta'); // Alternar la visibilidad de la tarjeta de la sede
    relatedSpaces.forEach(space => space.classList.toggle('oculta')); // Alternar la visibilidad de los espacios relacionados
}