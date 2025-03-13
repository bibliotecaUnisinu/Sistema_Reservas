<!-- Modal Principal para Crear Reserva -->
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
                <form id="formulario" action="../funciones/procesar_reserva.php" method="POST" autocomplete="off" onsubmit="return validarFormulario()">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="error-message" class="error-message text-danger" style="display: none;"></div>
                            <div class="form-floating mb-3">
                                <input type="text" id="identificacion" name="id" class="form-control" placeholder="Identificación" required pattern="\d{8,11}" minlength="8" maxlength="11" title="Debe tener entre 8 y 11 números" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <label for="id">Identificacion</label>
                                <div class="invalid-feedback">El campo debe contener entre 8 y 11 números.</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono" required maxlength="10" pattern="\d{10}" title="Debe tener 10 números" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <label for="telefono">Teléfono</label>
                                <div class="invalid-feedback">El campo debe contener 10 números.</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required pattern="[A-Za-z\s]+" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                <label for="nombre">Nombre</label>
                                <div class="invalid-feedback">El campo debe contener solo letras.</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido" required pattern="[A-Za-z\s]+" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                <label for="apellido">Apellido</label>
                                <div class="invalid-feedback">El campo debe contener solo letras.</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="mb-3">
                                <select id="tipo_usuario" name="tipo_usuario" class="form-select" required onchange="ocultarMensaje('mensajeTipoUsuario')">
                                    <option disabled selected>Tipo de usuario</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Estudiante">Estudiante</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                                <div id="mensajeTipoUsuario" class="text-danger" style="display: none;">Por favor, selecciona un tipo de usuario.</div>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea id="observacion" name="observacion" class="form-control" placeholder="Por favor, ingresa detalles específicos aquí..." style="height: 100px;" onfocus="ocultarMensaje('mensajeAyudaObservacion')"></textarea>
                                <label for="observacion">Observación</label>
                                <div id="mensajeAyudaObservacion" class="text-danger" style="display: block;">Si necesitas algo adicional, como un PC, una silla o más cupos, escríbelo aquí.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 position-relative">
                                <input type="text" id="fecha_reserva" name="fecha_reserva" class="form-control" placeholder="Seleccione una fecha" required>
                                <label for="fecha_reserva">Fecha de reserva</label>
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
                                <div id="mensajeDuracion" class="text-danger" style="display: none;">Debes elegir al menos una duración en horas o minutos.</div>
                            </div>
                            <div class="mb-3">
                                <label for="minutos_reservas">Minutos de reserva:</label>
                                <select id="minutos_reservas" name="minutos_reservas" class="form-select" required>
                                    <option disabled selected>Minutos reserva</option>
                                    <option value="0">0 minutos</option>
                                    <option value="15">15 minutos</option>
                                    <option value="30">30 minutos</option>
                                    <option value="45">45 minutos</option>
                                </select>
                                <div id="mensajeMinutos" class="text-danger" style="display: none;">El tiempo no puede ser menor a 15 minutos.</div>
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
                                <div id="mensajeAsistentes" class="text-danger" style="display: none;">El número de asistentes no puede ser mayor a la capacidad del espacio.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnCrearReserva" disabled>Crear Reserva</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const fechaReserva = flatpickr("#fecha_reserva", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    document.getElementById('iconoCalendario').addEventListener('click', function() {
        fechaReserva.open();
    });

    function ocultarMensaje(mensajeId) {
        document.getElementById(mensajeId).style.display = 'none';
    }

    function validarFormulario() {
        let isValid = true;

        // Definir los campos a validar y sus mensajes de error
        const camposValidar = [
            { id: "Horas_reservas", mensaje: "mensajeDuracion" },
            { id: "minutos_reservas", mensaje: "mensajeMinutos" },
            { id: "programa", mensaje: "mensajePrograma" },
            { id: "semestre", mensaje: "mensajeSemestre" },
            { id: "num_asistentes", mensaje: "mensajeAsistentes" }
        ];

        // Ocultar mensajes previos
        camposValidar.forEach(({ mensaje }) => {
            document.getElementById(mensaje).style.display = 'none';
        });

        // Validar duración o minutos (uno debe ser mayor a 0)
        const horas = document.getElementById("Horas_reservas").value;
        const minutos = document.getElementById("minutos_reservas").value;

        if (horas == "0" && minutos == "0") {
            document.getElementById("mensajeDuracion").style.display = 'block';
            document.getElementById("mensajeMinutos").style.display = 'block';
            isValid = false;
        }

        // Validar los demás campos
        camposValidar.forEach(({ id, mensaje }) => {
            const campo = document.getElementById(id);
            if (!campo.value || campo.value === "0" || campo.value === "Programa" || campo.value === "Semestre") {
                document.getElementById(mensaje).style.display = 'block';
                isValid = false;
            }
        });

        return isValid;
    }

    // Habilitar o deshabilitar el botón de crear reserva
    function validarCampos() {
        const horas = document.getElementById("Horas_reservas").value;
        const minutos = document.getElementById("minutos_reservas").value;

        const campos = ["programa", "semestre", "num_asistentes"];
        let valido = campos.every(campo => {
            const elemento = document.getElementById(campo);
            return elemento && elemento.value.trim() !== '' && elemento.value !== "0";
        });

        // Al menos uno de los dos (horas o minutos) debe ser mayor a 0
        if (horas === "0" && minutos === "0") {
            valido = false;
        }

        document.getElementById('btnCrearReserva').disabled = !valido;
    }

    // Asignar validaciones a los cambios en los campos
    document.querySelectorAll('input, select').forEach(elemento => {
        elemento.addEventListener('change', validarCampos);
        elemento.addEventListener('input', validarCampos);
    });

    // Evento de envío del formulario
    document.getElementById("formulario").addEventListener("submit", function(event) {
        if (!validarFormulario()) {
            event.preventDefault();
        }
    });

    // Ocultar mensajes de error al hacer clic en los campos
    const camposValidar = [
        { id: "Horas_reservas", mensaje: "mensajeDuracion" },
        { id: "minutos_reservas", mensaje: "mensajeMinutos" },
        { id: "programa", mensaje: "mensajePrograma" },
        { id: "semestre", mensaje: "mensajeSemestre" },
        { id: "num_asistentes", mensaje: "mensajeAsistentes" }
    ];

    camposValidar.forEach(({ id, mensaje }) => {
        const campo = document.getElementById(id);
        if (campo) {
            campo.addEventListener('focus', () => {
                ocultarMensaje(mensaje);
            });
        }
    });
});
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
