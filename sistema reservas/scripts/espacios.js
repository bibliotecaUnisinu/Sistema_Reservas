let selectedSpace = {};

// Función para abrir el modal con los detalles del espacio
function openModal(name, capacity, location, id, state) {
    document.getElementById("modal-name").textContent = name;
    document.getElementById("modal-capacity").textContent = capacity;
    document.getElementById("modal-location").textContent = location;

    selectedSpace = {
        id,
        name,
        capacity,
        location,
        state
    };
    document.getElementById("espacioModal").style.display = "block";
}

// Cerrar el modal de detalles
function closeModal() {
    document.getElementById("espacioModal").style.display = "none";
}

// Abrir el modal de edición con los datos del espacio actual
function openEditModal() {
    closeModal(); // Cerrar el modal de detalles
    document.getElementById("edit-id-space").value = selectedSpace.id;
    document.getElementById("edit-name-space").value = selectedSpace.name;
    document.getElementById("edit-capacity").value = selectedSpace.capacity;
    document.getElementById("edit-location").value = selectedSpace.location;
    document.getElementById("edit-state-space").checked = selectedSpace.state == 1;
    document.getElementById("editModal").style.display = "block"; // Abrir el modal de edición
}

// Cerrar el modal de edición
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// Confirmación de envío del formulario de edición
document.getElementById('editForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevenir el envío del formulario
    document.getElementById('modalConfirmacion').style.display = 'block'; // Mostrar modal de confirmación
});

// Aceptar la edición y enviar el formulario
document.getElementById('btnAceptar').onclick = function () {
    document.getElementById('editForm').submit(); // Enviar el formulario
};

// Cancelar la edición
document.getElementById('btnCancelar').onclick = function () {
    document.getElementById('modalConfirmacion').style.display = 'none'; // Cerrar modal de confirmación
};