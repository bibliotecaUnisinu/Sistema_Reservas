<!-- Modal Principal para Crear Reserva -->
<!-- Agregar Flatpickr (Selector de fecha y hora) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="modal fade" id="modal_formulario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Reserva</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formulario" action="../funciones/procesar_reservas_admin.php" method="POST" autocomplete="off" onsubmit="return validarFormulario()">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Muestra errores si existen -->
                                    <?php if (isset($_GET['error'])): ?>
                                    <div id="error-message" class="error-message text-danger">
                                        <?php echo htmlspecialchars($_GET['error']); ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="form-floating mb-3">
                                    <input type="text" id="identificacion" name="id" class="form-control" placeholder="Identificación" required pattern="\d{8,11}" minlength="8" maxlength="11" title="Debe tener entre 8 y 11 números">
                                        <label for="id">Identificacion</label>
                                        <div class="invalid-feedback">El campo debe contener 8 números.</div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="id" name="telefono" class="form-control" placeholder="Teléfono" required pattern="\d{10}" title="Debe tener 10 números">
                                        <label for="telefono">Teléfono</label>
                                        <div class="invalid-feedback">El campo debe contener 10 números.</div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                                        <label for="nombre">Nombre</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido" required>
                                        <label for="apellido">Apellido</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                                        <label for="email">Email</label>
                                    </div>

                                    <div class="mb-3">
                                        <select id="tipo_usuario" name="tipo_usuario" class="form-select" required>
                                            <option disabled selected>Tipo de usuario</option>
                                            <option value="Docente">Docente</option>
                                            <option value="Estudiante">Estudiante</option>
                                            <option value="Administrador">Administrador</option>
                                        </select>
                                        <div id="mensajeTipoUsuario" class="text-danger" style="display: none;">Por favor, selecciona un tipo de usuario.</div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea id="observacion" name="observacion" class="form-control" placeholder="Por favor, ingresa detalles específicos aquí..." style="height: 100px;" onfocus="mostrarMensaje(this)" oninput="ocultarMensaje(this)"></textarea>
                                        <label for="observacion">Observación</label>
                                        <div id="mensaje" class="text-danger" style="display: none;">Por favor, ingresa detalles específicos aquí... (ej. si necesitas un PC, una silla más, o si van más integrantes)</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-floating mb-3 position-relative">
                                <input type="text" id="fecha_reserva" name="fecha_reserva" class="form-control" placeholder="Seleccione una fecha" required>
                                <label for="fecha_reserva">Fecha de reserva</label>
                                <!-- Icono de calendario -->
                                <i class="fas fa-calendar-alt position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" id="iconoCalendario"></i>
                            </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="fecha_actual" name="fecha_actual" class="form-control" readonly>
                                        <label for="fecha_actual">Fecha de hoy</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="sede_seleccionada" name="sede_seleccionada" class="form-control" placeholder="Seleccione una sede" required>
                                        <label for="sede_seleccionada">Sede</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" id="espacio_seleccionada" name="espacio_seleccionada" class="form-control" placeholder="Seleccione un espacio" required>
                                        <label for="espacio_seleccionada">Espacio</label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="hora_inicio">Hora de inicio:</label>
                                        <input type="time" id="hora_inicio" name="horaInicio" class="form-control" value="06:30" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="Horas_reservas">Duración:</label>
                                        <select id="Horas_reservas" name="Horas_reservas" class="form-select">
                                            <option disabled selected>Horas reserva</option>
                                            <option value="0">0 horas</option>
                                            <option value="1">1 hora</option>
                                            <option value="2">2 horas</option>
                                            <option value="3">3 horas</option>
                                            <option value="4">4 horas</option>
                                        </select>
                                        <div id="mensajeDuracion" class="text-danger" style="display: none;">Por favor, selecciona al menos una duración (horas o minutos).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="minutos_reservas">Minutos de reserva:</label>
                                        <select id="minutos_reservas" name="minutos_reservas" class="form-select">
                                            <option disabled selected>Minutos reserva</option>
                                            <option value="0">0 minutos</option>
                                            <option value="15">15 minutos</option>
                                            <option value="30">30 minutos</option>
                                            <option value="45">45 minutos</option>
                                        </select>
                                        <div id="mensajeMinutos" class="text-danger" style="display: none;">Por favor, selecciona al menos una duración (horas o minutos).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="programa">Programa:</label>
                                        <select id="programa" name="programa" class="form-select" required>
                                            <option disabled selected>Programa</option>
                                            <option value="Medicina">Medicina</option>
                                            <option value="Sistemas">Sistemas</option>
                                            <option value="Contaduría">Contaduría</option>
                                            <option value="Industrial">Industrial</option>
                                            <option value="Odontología">Odontología</option>
                                            <option value="Enfermería">Enfermería</option>
                                        </select>
                                        <div id="mensajePrograma" class="text-danger" style="display: none;">Por favor, selecciona un programa.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="semestre">Semestre:</label>
                                        <select id="semestre" name="semestre" class="form-select" required>
                                            <option disabled selected>Semestre</option>
                                            <option value="otro">Otro</option>
                                            <option value="1">1 Semestre</option>
                                            <option value="2">2 Semestre</option>
                                            <option value="3">3 Semestre</option>
                                            <option value="4">4 Semestre</option>
                                            <option value="5">5 Semestre</option>
                                            <option value="6">6 Semestre</option>
                                            <option value="7">7 Semestre</option>
                                            <option value="8">8 Semestre</option>
                                            <option value="9">9 Semestre</option>
                                        </select>
                                        <div id="mensajeSemestre" class="text-danger" style="display: none;">Por favor, selecciona un semestre.</div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="number" id="num_asistentes" name="num_asistentes" class="form-control" placeholder="Número de personas" required>
                                        <label for="num_asistentes">Número de asistentes</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Botones de envío y cierre -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Crear Reserva</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fechaReserva = flatpickr("#fecha_reserva", {
                enableTime: false,  // Solo permite seleccionar la fecha
                dateFormat: "Y-m-d", // Formato Año-Mes-Día
                minDate: "today" // No permite fechas pasadas
            });

            // Configurar el evento de clic en el icono de calendario
            document.getElementById('iconoCalendario').addEventListener('click', function () {
                fechaReserva.open(); // Abre el selector de fecha
            });
        });
    </script>

        <script>
        function mostrarMensaje(textarea) {
            if (textarea.value === '') {
                document.getElementById('mensaje').style.display = 'block'; // Muestra el mensaje
            }
        }

        function ocultarMensaje(textarea) {
            if (textarea.value !== '') {
                document.getElementById('mensaje').style.display = 'none'; // Oculta el mensaje al escribir
            }
        }

        function validarFormulario() {
            const id = document.getElementById('id').value;
            const telefono = document.getElementById('telefono').value;
            const horasReservas = document.getElementById('Horas_reservas').value;
            const minutosReservas = document.getElementById('minutos_reservas').value;
            const programa = document.getElementById('programa').value;
            const semestre = document.getElementById('semestre').value;
            const tipoUsuario = document.getElementById('tipo_usuario').value; // Obtener el valor del tipo de usuario

            // Limpiar mensajes anteriores
            document.getElementById('mensajeDuracion').style.display = 'none';
            document.getElementById('mensajeMinutos').style.display = 'none';
            document.getElementById('mensajeTipoUsuario').style.display = 'none';
            document.getElementById('mensajePrograma').style.display = 'none';
            document.getElementById('mensajeSemestre').style.display = 'none';

            // if (id.length >= 8 || id.length <=11) {
            //      alert("El campo de identificación debe contener minimo 8 números.");
            //    return false;
            // }

            if (telefono.length !== 10) {
                alert("El campo de teléfono debe contener exactamente 10 números.");
                return false;
            }

            // Validar que el tipo de usuario esté seleccionado
            if (tipoUsuario === "Tipo de usuario") {
                document.getElementById('mensajeTipoUsuario').style.display = 'block'; // Muestra el mensaje
                return false;
            }

            // Validar que al menos uno de los dos valores (Horas_reservas o minutos_reservas) sea mayor que 0
            if (horasReservas === "Horas reserva" && minutosReservas === "Minutos reserva") {
                document.getElementById('mensajeDuracion').style.display = 'block'; // Muestra el mensaje
                return false;
            }

            // Validar que los campos de programa y semestre no estén vacíos
            if (programa === "Programa") {
                document.getElementById('mensajePrograma').style.display = 'block'; // Muestra el mensaje
                return false;
            }

            if (semestre === "Semestre") {
                document.getElementById('mensajeSemestre').style.display = 'block'; // Muestra el mensaje
                return false;
            }

            return true; // Permitir el envío del formulario
        }
        function validarReserva() {
            const minutosReservas = document.getElementById('minutos_reservas').value;
            const horasReservas = document.getElementById('Horas_reservas').value;

            const mensajeMinutos = document.getElementById('mensajeMinutos');
            const mensajeHoras = document.getElementById('mensajeHoras');

            // Ocultar mensajes de error al inicio
            mensajeMinutos.style.display = 'none';
            mensajeHoras.style.display = 'none';

            // Validar si se ha seleccionado al menos un valor
            if (minutosReservas == "0" && horasReservas == "0") {
                mensajeMinutos.style.display = 'block';
                mensajeHoras.style.display = 'block';
                return false; // Evitar el envío del formulario
            }
                };
        </script>



<!-- Modal para Detalles del Evento -->
<div class="modal fade" id="modalDetallesEvento" tabindex="-1" role="dialog" aria-labelledby="modalDetallesEventoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detalles del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <p><strong>Título:</strong> <span id="reservaTitulo"></span></p>
                <p><strong>Fecha de Inicio:</strong> <span id="reservaFechaInicio"></span></p>
                <p><strong>Fecha de Fin:</strong> <span id="reservaFechaFin"></span></p>
                <p><strong>Descripción:</strong> <span id="reservaDescripcion"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#modalDetallesEvento').modal('hide')">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<div class="container mt-5">
    <div class="d-flex justify-content-between">

        <!-- Seleccionar sede -->
        <select id="sedeSelect" class="form-select w-auto">
            <option disabled selected>Seleccionar sede</option>
            <?php if ($resultSedes && $resultSedes->num_rows > 0): ?>
            <?php while ($sede = $resultSedes->fetch_assoc()): ?>
            <option value="<?php echo $sede['id_location']; ?>">
                <?php echo $sede['name_location']; ?>
            </option>
            <?php endwhile; ?>
            <?php else: ?>
            <option disabled>No hay sedes disponibles</option>
            <?php endif; ?>
        </select>

        <!-- Seleccionar espacio -->
        <select id="espacioSelect" class="form-select w-auto" disabled>
            <option disabled selected>Seleccionar espacio</option>
        </select>
        <div id="Botones">
            <button class="btn btn-outline-primary" id="btn-hoy">Hoy</button>
            <button class="btn btn-outline-primary" id="btn-dia">Día</button>
            <button class="btn btn-outline-primary" id="btn-semana">Semana</button>
            <button class="btn btn-outline-primary" id="btn-mes">Mes</button>
            <button class="btn btn-outline-primary" id="btn-lista">Lista</button>
        </div>
    </div>
