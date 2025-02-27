function validarFormulario(event) {
    // Prevenir el envío del formulario
    event.preventDefault();

    // Obtiene los valores de los campos
    const nombreSede = document.getElementById('nombreSede').value.trim();
    const direccion = document.getElementById('direccion').value.trim();
    const contacto = document.getElementById('contacto').value.trim();
    const errorBanner = document.getElementById('errorBanner');
    const errorMessages = []; // Array para almacenar mensajes de error

    // Verifica si algún campo está vacío
    if (!nombreSede) {
        errorMessages.push("El nombre de la sede es obligatorio.");
    }
    if (!direccion) {
        errorMessages.push("La dirección es obligatoria.");
    }
    if (!contacto) {
        errorMessages.push("El contacto es obligatorio.");
    }

    // Mostrar mensajes de error si hay alguno
    if (errorMessages.length > 0) {
        errorBanner.innerHTML = errorMessages.join('<br>'); // Mostrar todos los mensajes de error
        errorBanner.style.display = 'block'; // Mostrar el banner de error
        return false; // Evita que el formulario se envíe
    }

    // Oculta el banner de error si todos los campos están completos
    errorBanner.style.display = 'none';
    return true; // Permite que el formulario se envíe
}

// Asignar la función de validación al evento submit del formulario
document.getElementById('miFormulario').addEventListener('submit', validarFormulario);