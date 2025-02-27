document.getElementById('formulario').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío del formulario
    
    // Crear un objeto FormData para enviar los datos del formulario
    const formData = new FormData(this);

    // Realizar la solicitud AJAX
    fetch('../funciones/procesar_reserva.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Limpiar mensajes anteriores
        document.getElementById('mensaje_asistentes').style.display = 'none';

        // Mostrar el mensaje en el formulario
        if (data.success) {
            alert(data.message); // Mensaje de éxito
            window.location.href = '../Visualizaciones/inicio.php'; // Redirigir a otra página
        } else {
            // Mostrar el mensaje de error en el formulario
            document.getElementById('mensaje_asistentes').innerText = data.message;
            document.getElementById('mensaje_asistentes').style.display = 'block'; // Mostrar mensaje de error
        }
    })
    .catch(error => console.error('Error:', error));
});
