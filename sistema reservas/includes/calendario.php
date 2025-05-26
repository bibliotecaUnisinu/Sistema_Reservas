<!-- Contenedor del calendario -->
<div id="calendar" class="mt-4"></div>

<!-- Scripts de jQuery y Bootstrap -->
<script src="https://smtpjs.com/v3/smtp.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sedeSeleccionada = false; // Variable para verificar si se ha seleccionado una sede
        var espacioSeleccionado = false; // Variable para verificar si se ha seleccionado un espacio
        var calendarEl = document.getElementById('calendar'); // Obtener el elemento del calendario

        // Configuración de opciones para el formato de hora que se usará globalmente
        const intervalos = [
        ["06:30:00", "10:00:00"],
        ["10:00:00", "13:30:00"],
        ["13:30:00", "17:00:00"],
        ["17:00:00", "21:00:00"]
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
        left: 'prev',
        center: 'title',
        right: 'next'
    },
    events: [],
    dayMaxEvents: 4,
    editable: false,
    eventStartEditable: false,
    eventAllow: function (dropLocation, draggedEvent) {
        return false;
    },
    
    dayMaxEvents: 4, // Limitar a 4 eventos por día en la vista de mes

    views: {
        dayGridMonth: {
            eventContent: function(arg) {
                // En la vista de mes, solo mostrar la hora
                if (arg.event.start) {
                    const hours = arg.event.start.getHours() % 12 || 12; // Convertir a formato de 12 horas
                    const minutes = arg.event.start.getMinutes().toString().padStart(2, '0');
                    const ampm = arg.event.start.getHours() >= 12 ? 'PM' : 'AM'; // Determinar AM o PM
                    return {
                        html: `<div class="fc-content">
                            <div class="fc-time">${hours}:${minutes} ${ampm}</div>
                        </div>`
                    };
                }
                return '';
            }
        },
        timeGridDay: {
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true // Formato de 12 horas
            }
        },
        timeGridWeek: {
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true // Formato de 12 horas
            }
        }
    },

    eventTimeFormat: { // Formato de hora para los eventos
        hour: 'numeric',
        minute: '2-digit',
        hour12: true // Formato de 12 horas
    },

    selectable: true,
    editable: true,
    
    // Función para validar que las reservas solo se puedan hacer en los intervalos definidos
    selectConstraint: {
        startTime: '06:30:00',
        endTime: '21:00:00'
    },

   
    dateClick: function(info) {
    manejarClickEnFecha(info.dateStr);
},

eventClick: function(info) {
    info.jsEvent.preventDefault();
    const evento = info.event;

    const sedeSeleccionada = document.getElementById('sedeSelect').value;
    const espacioSeleccionado = document.getElementById('espacioSelect').value;

    if (!sedeSeleccionada || !espacioSeleccionado) {
        Swal.fire({
            icon: 'warning',
            title: 'Sede y Espacio Requeridos',
            text: 'Por favor selecciona una sede y un espacio antes de continuar.'
        });
        return;
    }

    if (evento.extendedProps.tipo === 'disponibilidad') {
        const fechaStr = evento.startStr.split('T')[0];

        document.getElementById('fecha_reserva').value = fechaStr;
        document.getElementById('espacio_seleccionada').value = evento.title.replace("Disponible: ", "");

        // Mostrar horarios disponibles de ese día
        const disponibles = calendar.getEvents().filter(ev =>
            ev.extendedProps.tipo === 'disponibilidad' &&
            ev.startStr.startsWith(fechaStr)
        );

        let htmlHorarios = disponibles.map(ev => {
            const inicio = ev.startStr.split('T')[1].slice(0, 5);
            const fin = ev.endStr.split('T')[1].slice(0, 5);
            return `<b>${inicio} - ${fin}</b>`;
        }).join('<br>');

        Swal.fire({
            icon: 'info',
            title: `Horarios disponibles para el ${fechaStr}`,
            html: htmlHorarios,
            confirmButtonText: 'Reservar este día',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#modal_formulario').modal("show");
            }
        });

    } else if (evento.extendedProps.tipo === 'reserva') {
        const startTime = evento.start ? formatTime(evento.start) : '';
        const endTime = evento.end ? formatTime(evento.end) : '';
        const idReserva = evento.extendedProps.id_reservation;
        const descripcion = evento.extendedProps.observation;

        // Mostrar modal de detalles de la reserva
        document.getElementById('reservaId').value = idReserva;
        document.getElementById('reservaIdMostrar').innerText = idReserva;

        document.getElementById('reservaTitulo').innerText = evento.title;
        document.getElementById('reservaFechaInicio').innerText =
            `${evento.start.toLocaleDateString()} ${startTime}`;
        document.getElementById('reservaFechaFin').innerText =
            evento.end ? `${evento.end.toLocaleDateString()} ${endTime}` : 'No especificado';
        document.getElementById('reservaDescripcion').innerText = descripcion || 'Sin descripción';

        document.getElementById('sede_seleccionada_modal').value = sedeSeleccionada;
        document.getElementById('espacio_seleccionada_modal').value = espacioSeleccionado;

        $('#modalDetallesEvento').modal("show");
    }
}

,
    selectable: true,
    editable: true,
    titleFormat: function(info) {
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return meses[info.date.month] + ' ' + info.date.year;
    }
});

    // Función auxiliar para formatear la hora
    function formatTime(date) {
    const hours = date.getHours() % 12 || 12; // Convertir a formato de 12 horas
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = date.getHours() >= 12 ? 'PM' : 'AM'; // Determinar AM o PM
    return `${hours}:${minutes} ${ampm}`;
}

        // Forzar el cierre del modal al hacer clic en el botón de cerrar o la "X"
        $('#modalDetallesEvento').on('hidden.bs.modal', function (e) {
            e.preventDefault();
            $('#modalDetallesEvento').modal('hide');
            console.log("El modal de detalles del evento se ha cerrado.");
        });

        calendar.render(); // Renderizar el calendario
        // Botones de cambio de vista
        document.getElementById('btn-dia').addEventListener('click', function() {
            calendar.changeView('timeGridDay'); // Cambiar a vista de día
        });
        document.getElementById('btn-semana').addEventListener('click', function() {
            calendar.changeView('timeGridWeek'); // Cambiar a vista de semana
        });
        document.getElementById('btn-mes').addEventListener('click', function() {
            calendar.changeView('dayGridMonth'); // Cambiar a vista de mes
        });
        document.getElementById('btn-lista').addEventListener('click', function() {
            calendar.changeView('listWeek'); // Cambiar a vista de lista
        });
        document.getElementById('btn-hoy').addEventListener('click', function() {
            calendar.today(); // Ir a la fecha actual
        });

        // Manejar la selección de sede y actualizar espacios
        document.getElementById('sedeSelect').addEventListener('change', function() {
            sedeSeleccionada = true; // Marcar que se ha seleccionado una sede
            var selectedValue = this.value;
            var selectedText = this.options[this.selectedIndex].text;
            document.getElementById('sede_seleccionada').value = selectedText; // Asignar el texto de la sede seleccionada

            const espacioSelect = document.getElementById('espacioSelect');
            espacioSelect.innerHTML = '<option disabled selected>Seleccionar espacio</option>'; // Limpiar opciones anteriores
            espacioSelect.disabled = false; // Habilitar el selector de espacios

            const espacios = <?php echo json_encode($espaciosPorSede); ?>; // Obtener espacios desde PHP

            if (espacios[selectedValue]) {
                espacios[selectedValue].forEach(function(espacio) {
                    const option = document.createElement('option');
                    option.value = espacio.id_space;
                    option.textContent = espacio.name_space; // Asignar nombre del espacio
                    espacioSelect.appendChild(option); // Agregar opción al selector
                });
            }

            cargarEventos(); // Llamada a la función de carga de eventos
        });

        // Manejar la selección de espacio y actualizar el formulario modal
        document.getElementById('espacioSelect').addEventListener('change', function() {
            espacioSeleccionado = true; // Marcar que se ha seleccionado un espacio
            var selectedEspacioText = this.options[this.selectedIndex].text;
            document.getElementById('espacio_seleccionada').value = selectedEspacioText; // Asignar el texto del espacio seleccionado

            cargarEventos(); // Llamada a la función de carga de eventos
        });

        // Función para cargar eventos solo si ambos campos están seleccionados
        function cargarEventos() {
            if (sedeSeleccionada && espacioSeleccionado) {
                calendar.removeAllEvents(); // Limpiar eventos anteriores
                calendar.addEventSource({
                    url: '../funciones/eventos.php', // URL para cargar eventos
                    method: 'GET',
                    extraParams: {
                        sede: document.getElementById('sedeSelect').value, // Pasar la sede seleccionada
                        espacio: document.getElementById('espacioSelect').value // Pasar el espacio seleccionado
                    },
                    failure: function() {
                        console.error('Error al cargar los eventos');
                        alert('No se pudieron cargar los eventos. Revisa la consola para más detalles.');
                    }
                });
            }
        }

        function manejarClickEnFecha(fechaStr) {
    const fechaSeleccionada = new Date(fechaStr);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    if (!sedeSeleccionada || !espacioSeleccionado) {
        Swal.fire({
            icon: 'warning',
            title: 'Sede y Espacio Requeridos',
            text: 'Por favor selecciona una sede y un espacio antes de continuar.'
        });
        return;
    }

    if (fechaSeleccionada < hoy) {
        Swal.fire({
            icon: 'error',
            title: 'Fecha Inválida',
            text: 'No se pueden hacer reservas para fechas anteriores.'
        });
        return;
    }

    if (fechaSeleccionada.getDay() === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Domingo No Disponible',
            text: 'No hay reservas disponibles los domingos.'
        });
        return;
    }

    const todosEventos = calendar.getEvents();

    const bloquesDisponibles = calendar.getEvents().filter(evento =>
        evento.extendedProps.tipo === 'disponibilidad' &&
        evento.startStr.startsWith(fechaStr)
    );

    const reservas = todosEventos.filter(evento =>
        evento.extendedProps.tipo === 'reserva' &&
        evento.startStr.startsWith(fechaStr)
    );

    if (bloquesDisponibles.length === 0) {
        Swal.fire({
            icon: 'info',
            title: `Sin disponibilidad el ${fechaStr}`,
            text: 'No hay horarios disponibles para reservar en este día.'
        });
        return;
    }

    // Crear un arreglo con todos los bloques horarios disponibles sin superposición de reservas
    const bloquesFinales = [];

    bloquesDisponibles.forEach(bloque => {
        let inicio = new Date(bloque.start);
        let fin = new Date(bloque.end);

        let segmentos = [{ inicio, fin }];

        reservas.forEach(res => {
            const resInicio = new Date(res.start);
            const resFin = new Date(res.end);

            // Procesar cada segmento y dividir si hay intersección
            segmentos = segmentos.flatMap(seg => {
                if (resInicio >= seg.fin || resFin <= seg.inicio) {
                    return [seg]; // no hay superposición
                }

                const nuevos = [];

                if (resInicio > seg.inicio) {
                    nuevos.push({ inicio: seg.inicio, fin: resInicio });
                }

                if (resFin < seg.fin) {
                    nuevos.push({ inicio: resFin, fin: seg.fin });
                }

                return nuevos;
            });
        });

        bloquesFinales.push(...segmentos);
    });

    if (bloquesFinales.length === 0) {
        Swal.fire({
            icon: 'info',
            title: `Día Ocupado`,
            text: 'Todos los bloques están ocupados para este día.'
        });
        return;
    }

    // Mostrar los bloques disponibles reales
    let htmlHorarios = bloquesFinales.map(seg => {
        const inicio = seg.inicio.toTimeString().slice(0, 5);
        const fin = seg.fin.toTimeString().slice(0, 5);
        return `<b>${inicio} - ${fin}</b>`;
    }).join('<br>');

    Swal.fire({
        icon: 'info',
        title: `Bloques disponibles el ${fechaStr}`,
        html: htmlHorarios,
        confirmButtonText: 'Reservar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('fecha_reserva').value = fechaStr;
            $('#modal_formulario').modal("show");
        }
    });
}



        // Configurar la fecha actual en el modal
        var fechaActual = document.getElementById('fecha_actual');
        var hoy = new Date();
        var dia = hoy.getDate();
        var mes = hoy.getMonth() + 1; // Los meses son 0-indexed
        var anio = hoy.getFullYear();
        var fechaFormateada = `${anio}-${mes < 10 ? '0' + mes : mes}-${dia < 10 ? '0' + dia : dia}`; // Formato YYYY-MM-DD
        fechaActual.value = fechaFormateada; // Asignar la fecha formateada al campo

        // Bloquear edición de ciertos campos
        const cedulaInput = document.getElementById("id");
        const nombreInput = document.getElementById("nombre");
        const apellidoInput = document.getElementById("apellido");        const telefonoInput = document.getElementById("telefono");
        const fechaReservaInput = document.getElementById("fecha_reserva");
        const fechaActualInput = document.getElementById("fecha_actual");
        const sedeInput = document.getElementById("sede_seleccionada");
        const espacioInput = document.getElementById("espacio_seleccionada");

        // Bloquear la edición de ciertos campos
        fechaReservaInput.readOnly = true;
        fechaActualInput.readOnly = true;
        sedeInput.readOnly = true;
        espacioInput.readOnly = true;

        // Solo permite números y limita a 10 dígitos para la cédula
        cedulaInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9]/g, ''); 
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10); 
            }
        });

        // Función para validar nombre y apellido, solo permite letras y espacios, limita a 15 caracteres
        nombreInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, ''); 
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15); 
            }
        });

        // Solo permite letras y espacios y limita a 15 caracteres para el apellido
        apellidoInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, ''); 
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15); 
            }
        });

        // Función para validar el teléfono, solo permite números, limita a 10 dígitos
        telefonoInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9]/g, ''); 
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10); 
            }
        });

        // Llamar a la función para abrir el modal de edición
        $(document).on('click', '.btn-editar', function() {
            const idReserva = $(this).data('id'); 
            abrirModalEdicion(idReserva); 
        });

        // Función para abrir el modal de edición de reserva
        function openModalEditarReserva(reserva) {
            document.getElementById("id_reserva").value = reserva.id; // Asignar ID de la reserva
            document.getElementById("editar_cedula").value = reserva.cedula; // Asignar cédula
            document.getElementById("editar_nombre").value = reserva.nombre; // Asignar nombre
            $('#modal_editar_reserva').modal('show'); // Mostrar el modal de edición
        }
    });
</script>