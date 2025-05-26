$(document).ready(function () {
    // Botón "Deshabilitar Reserva"
    $('#btnDeshabilitar').on('click', function (e) {
        e.preventDefault();
        const reservaId = $('#reservaId').val();

        if (!reservaId || reservaId.trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'ID de reserva no válido.',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // Mostrar confirmación con SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción cancelará la reserva de forma permanente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, conservar',
            reverseButtons: true,
            focusCancel: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            customClass: {
                popup: 'animate__animated animate__fadeInDown',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                cancelarReserva(reservaId);
            }
        });
    });

    function cancelarReserva(reservaId) {
        $.ajax({
            url: '../funciones/actualizar_reserva.php',
            method: 'POST',
            data: { id_reserva: reservaId },
            beforeSend: function () {
                Swal.fire({
                    title: 'Cancelando reserva...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                try {
                    const res = JSON.parse(response);
                    if (res.estado === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Cancelada!',
                            text: 'La reserva fue cancelada exitosamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            recargarEventos();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.mensaje || 'No se pudo cancelar la reserva.'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo interpretar la respuesta del servidor.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud.'
                });
            }
        });
    }

    // Función para recargar los eventos
    function recargarEventos() {
        location.reload();
    }
});
