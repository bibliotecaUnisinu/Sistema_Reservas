// Obtener los elementos por ID
const passwordInput = document.getElementById('contrasena'); // Campo de entrada de contraseña
const togglePassword = document.getElementById('togglePassword'); // Botón para mostrar/ocultar contraseña
const loginForm = document.getElementById('loginForm'); // Formulario de inicio de sesión
const usuarioInput = document.getElementById('usuario'); // Campo de entrada de usuario
const errorBanner = document.getElementById('errorBanner'); // Banner para errores de campos vacíos
const loginErrorBanner = document.getElementById('loginErrorBanner'); // Banner para errores de credenciales

    // Mejorar la presentación de los eventos
events.forEach(event => {
    if (event.extendedProps.tipo === 'espacio') {
        event.color = '#28a745'; // Color verde para espacios disponibles
        event.display = 'block'; // Asegurarse de que se muestre
        event.title = "Disponible: " + event.title; // Prefijo para espacios disponibles
        event.textColor = '#ffffff'; // Color del texto en blanco para mejor contraste
    } else {
        event.color = '#dc3545'; // Color rojo para reservas
        event.textColor = '#ffffff'; // Color del texto en blanco para mejor contraste
    }
});

// Función para mostrar u ocultar la contraseña
togglePassword.addEventListener('click', function () {
    // Cambiar el tipo de input entre 'password' y 'text'
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    // Alternar las clases para el icono de mostrar/ocultar
    this.classList.toggle('bx-show');
    this.classList.toggle('bx-hide');
});

// Función para validar el formulario (modificado)
function validateForm() {
    let formIsValid = true; // Variable para controlar la validez del formulario

    // Ocultar ambos banners al comenzar la validación
    errorBanner.style.display = 'none';
    loginErrorBanner.style.display = 'none';

    // Validar el campo de usuario
    if (usuarioInput.value.trim() === '') {
        formIsValid = false; // Marcar el formulario como inválido si el campo está vacío
        // Mostrar mensaje de error específico (modificado)
        usuarioInput.nextElementSibling.textContent = 'El campo de usuario no puede estar vacío.'; // Asumiendo que hay un elemento para mostrar el error
    } else {
        usuarioInput.nextElementSibling.textContent = ''; // Limpiar mensaje de error
    }

    // Validar el campo de contraseña
    if (passwordInput.value.trim() === '') {
        formIsValid = false; // Marcar el formulario como inválido si el campo está vacío
        // Mostrar mensaje de error específico (modificado)
        passwordInput.nextElementSibling.textContent = 'El campo de contraseña no puede estar vacío.'; // Asumiendo que hay un elemento para mostrar el error
    } else {
        passwordInput.nextElementSibling.textContent = ''; // Limpiar mensaje de error
    }

    return formIsValid; // Retornar el estado de validez del formulario
}

// Validar el formulario al intentar enviarlo
loginForm.addEventListener('submit', function (event) {
    if (!validateForm()) { // Llamar a la función de validación
        event.preventDefault(); // Evitar el envío del formulario
        errorBanner.style.display = 'block'; // Mostrar el banner de campos vacíos
    }
});

// Mostrar error de credenciales incorrectas si el parámetro "error" está en la URL
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('error') && !errorBanner.style.display.includes('block')) {
    loginErrorBanner.style.display = 'block'; // Mostrar banner solo si no hay error por campos vacíos

    // Limpiar el parámetro "error" de la URL sin recargar la página
    if (window.history.replaceState) {
        const url = window.location.href.split('?')[0]; // Obtener la URL sin parámetros
        window.history.replaceState(null, null, url); // Reemplazar la URL en la barra de direcciones
    }
}