function validarFormulario(event) {
    // Prevenir el envío del formulario (modificado)
    event.preventDefault();

    // Obtener los valores de los campos (modificado)
    const nombreEspacio = document.getElementById('nombreEspacio').value.trim();
    const capacidad = document.getElementById('capacidad').value.trim();
    const sedeId = document.getElementById('sedeId').value.trim();
    const errorBanner = document.getElementById('errorBanner');
    const errorMessages = []; // (modificado) Array para almacenar mensajes de error

    // Verificar si algún campo está vacío (modificado)
    if (!nombreEspacio) {
        errorMessages.push("El nombre del espacio es obligatorio."); // (modificado)
    }
    if (!capacidad) {
        errorMessages.push("La capacidad es obligatoria."); // (modificado)
    } else if (isNaN(capacidad) || capacidad <= 0) {
        errorMessages.push("La capacidad debe ser un número positivo."); // (modificado)
    }
    if (!sedeId) {
        errorMessages.push("La sede es obligatoria."); // (modificado)
    }

    // Mostrar mensajes de error si hay alguno (modificado)
    if (errorMessages.length > 0) {
        errorBanner.innerHTML = errorMessages.join('<br>'); // (modificado) Mostrar todos los mensajes de error
        errorBanner.style.display = 'block'; // (modificado)
        return false; // Evitar el envío del formulario (modificado)
    }

    // Ocultar el banner de error si todos los campos están completos (modificado)
    errorBanner.style.display = 'none'; // (modificado)
    return true; // Permitir el envío del formulario (modificado)
}

// Asignar la función de validación al evento submit del formulario (modificado)
document.getElementById('miFormulario').addEventListener('submit', validarFormulario); // (modificado)