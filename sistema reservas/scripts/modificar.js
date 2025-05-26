// Manejo del botón modificar
$('#btnModificarReserva').click(function() {
    const reservaId = $('#reservaId').val();
    const fechaInicioActual = $('#reservaFechaInicio').text();
    const horaInicioActual = $('#reservaHoraInicio').text();

    // Establecer valores en el formulario de modificación
    $('#reservaIdModificacion').val(reservaId);
    $('#fechaInicioModificacion').val(fechaInicioActual);
    $('#horaInicioModificacion').val(horaInicioActual);

    // Mostrar modal de modificación con animación
    $('#modalModificarReserva').modal('show');
    $('#modalModificarReserva').addClass('zoomIn');
});

// Manejar el envío del formulario de modificación
$(document).ready(function() {
    $('#formularioModificarReserva').submit(function(e) {
        e.preventDefault();
        
        // Capturar los valores de horas y minutos (usando los nuevos IDs)
        const horas = parseInt($('#Horas_reservas_mod').val()) || 0;
        const minutos = parseInt($('#minutos_reservas_mod').val()) || 0;

        // Depuración: Mostrar los valores capturados de los select
        console.log('Valor de horas seleccionadas:', horas);
        console.log('Valor de minutos seleccionados:', minutos);

        // Validación en el Frontend
        if (horas === 0 && minutos === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No puede seleccionar 0 horas y 0 minutos.',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        if (horas > 4) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La duración máxima es de 4 horas.',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        // Preparar los datos para el envío
        const datos = {
            id_reservation: $('#reservaIdModificacion').val(),
            fecha_inicio: $('#fechaInicioModificacion').val(),
            hora_inicio: $('#horaInicioModificacion').val(),
            horas_reserva: horas,
            minutos_reserva: minutos
        };

        // Depuración: Mostrar los datos que se enviarán al backend
        console.log('Datos que se enviarán:', datos);

        // Mostrar animación de carga
        $('.btn-primary').prop('disabled', true).addClass('loading');

        $.ajax({
            url: '../funciones/modificaReserva.php',
            method: 'POST',
            data: datos,
            dataType: 'json',
            success: function(response) {
                if (response.estado === 'success') {
                    // Actualizar valores mostrados en el modal original
                    $('#reservaFechaInicio').text(datos.fecha_inicio);
                    $('#reservaHoraInicio').text(datos.hora_inicio);
                    $('#reservaDuracion').text(`${datos.horas_reserva} horas y ${datos.minutos_reserva} minutos`);

                    // Cerrar el modal de modificación con animación
                    $('#modalModificarReserva').modal('hide').removeClass('zoomIn');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Reserva Modificada!',
                        text: 'La reserva se ha modificado correctamente.',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animate__animated animate__fadeOut'
                        }
                    });
                } else {
                    alert('Error al modificar la reserva: ' + response.mensaje);
                }

                // Reactivar el botón después de la acción
                $('.btn-primary').prop('disabled', false).removeClass('loading');
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
                alert('Error al modificar la reserva: ' + error);

                // Reactivar el botón después de la acción
                $('.btn-primary').prop('disabled', false).removeClass('loading');
            }
        });
    });
});
