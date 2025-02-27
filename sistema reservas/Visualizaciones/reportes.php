<?php
// Incluir el archivo de configuración para la conexión
include('../conexion/config.php');

// Iniciar la sesión
session_start();

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    header("Location: ../Visualizaciones/login.php"); // Redirige al login si no hay sesión activa
    exit();
}

// Consultas para obtener opciones para los selectores
$tipos_usuario = $conexion->query("SELECT DISTINCT user_type FROM reservations");
$sedes = $conexion->query("SELECT DISTINCT location_select FROM reservations");
$programas = $conexion->query("SELECT DISTINCT program FROM reservations");
$semestres = $conexion->query("SELECT DISTINCT semester FROM reservations");

// Construir la consulta base
$sql = "SELECT * FROM reservations";
$filters = []; // Array para almacenar los filtros

// Función para agregar filtros a la consulta
function addFilter($condition) {
    global $filters; // Accede al array de filtros global
    $filters[] = $condition; // Agrega la condición al array de filtros
}

// Filtrar por tipo de usuario
if (!empty($_GET['tipo_usuario'])) {
    $tipo_usuario = $conexion->real_escape_string($_GET['tipo_usuario']);
    addFilter("user_type = '$tipo_usuario'");
}

// Filtrar por sede y espacio
if (!empty($_GET['sede'])) {
    $sede = $conexion->real_escape_string($_GET['sede']);
    addFilter("location_select = '$sede'");

    // Si también se seleccionó un espacio, filtrar por ambos
    if (!empty($_GET['espacio'])) {
        $espacio = $conexion->real_escape_string($_GET['espacio']);
        addFilter("space_select = '$espacio'");
    }
} elseif (!empty($_GET['espacio'])) {
    // Si no se seleccionó sede, pero sí espacio
    $espacio = $conexion->real_escape_string($_GET['espacio']);
    addFilter("space_select = '$espacio'");
}

// Filtrar por rango de fechas
if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
    $fecha_inicio = $conexion->real_escape_string($_GET['fecha_inicio']);
    $fecha_fin = $conexion->real_escape_string($_GET['fecha_fin']);
    addFilter("date_reserv BETWEEN '$fecha_inicio' AND '$fecha_fin'");
}

// Filtrar por programa
if (!empty($_GET['programa'])) {
    $programa = $conexion->real_escape_string($_GET['programa']);
    addFilter("program = '$programa'");
}

// Filtrar por semestre
if (!empty($_GET['semestre'])) {
    $semestre = $conexion->real_escape_string($_GET['semestre']);
    addFilter("semester = '$semestre'");
}

// Filtrar por estado de la reserva
if (!empty($_GET['estado_reserva'])) {
    $estado_reserva = $conexion->real_escape_string($_GET['estado_reserva']);
    addFilter("state_reservation = '$estado_reserva'");
}

// Filtrar por cancelación de la reserva
if (!empty($_GET['cancelada'])) {
    $cancelada = $conexion->real_escape_string($_GET['cancelada']);
    addFilter("cancel_reserv = '$cancelada'");
}

// Si hay filtros, añadirlos a la consulta SQL
if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters); // Agrega los filtros a la consulta SQL
}

// Ejecutar la consulta
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title>Reportes | Bibliotecas</title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluye scripts necesarios -->
    <script>
        // Función para actualizar los espacios según la sede seleccionada
        function actualizarEspacios() {
            const sede = document.getElementById("sede").value; // Obtiene el valor de la sede seleccionada
            const espacioSelect = document.getElementById("espacio"); // Obtiene el selector de espacios

            // Limpiar opciones de espacio
            espacioSelect.innerHTML = "<option value=''>Seleccione un espacio</option>";

            if (sede) {
                fetch(`obtener_espacios.php?sede=${sede}`) // Realiza una solicitud para obtener los espacios según la sede seleccionada
                    .then(response => response.json()) // Convierte la respuesta a formato JSON
                    .then(data => {
                        data.forEach(espacio => { // Itera sobre los espacios obtenidos
                            const option = document.createElement("option"); // Crea un nuevo elemento de opción
                            option.value = espacio.space_select; // Establece el valor de la opción
                            option.textContent = espacio.space_select; // Establece el texto de la opción
                            espacioSelect.appendChild(option); // Agrega la opción al selector de espacios
                        });
                    });
            }
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?> <!-- Incluye el encabezado de la página -->
    <h1>Reportes</h1> <!-- Título de la sección de reportes -->

    <!-- Formulario de búsqueda avanzado -->
    <form method="GET" action="reportes.php"> <!-- Formulario que envía datos a reportes.php -->
        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select id="tipo_usuario" name="tipo_usuario"> <!-- Selector para tipo de usuario -->
            <option value="">Seleccione tipo</option>
            <?php while ($row = $tipos_usuario->fetch_assoc()): ?> <!-- Itera sobre los tipos de usuario -->
                <option value="<?php echo htmlspecialchars($row['user_type']); ?>"><?php echo htmlspecialchars($row['user_type']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="sede">Sede:</label>
        <select id="sede" name="sede" onchange="actualizarEspacios()"> <!-- Selector para sede -->
            <option value="">Seleccione sede</option>
            <?php while ($row = $sedes->fetch_assoc()): ?> <!-- Itera sobre las sedes -->
                <option value="<?php echo htmlspecialchars($row['location_select']); ?>"><?php echo htmlspecialchars($row['location_select']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="espacio">Espacio:</label>
        <select id="espacio" name="espacio"> <!-- Selector para espacio -->
            <option value="">Seleccione un espacio</option>
        </select>

        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio"> <!-- Campo de entrada para la fecha de inicio -->

        <label for="fecha_fin">Fecha Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin"> <!-- Campo de entrada para la fecha de fin -->

        <label for="programa">Programa:</label>
        <select id="programa" name="programa"> <!-- Selector para programa -->
            <option value="">Seleccione programa</option>
            <?php while ($row = $programas->fetch_assoc()): ?> <!-- Itera sobre los programas -->
                <option value="<?php echo htmlspecialchars($row['program']); ?>"><?php echo htmlspecialchars($row['program']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="semestre">Semestre:</label>
        <select id="semestre" name="semestre"> <!-- Selector para semestre -->
            <option value="">Seleccione semestre</option>
            <?php while ($row = $semestres->fetch_assoc()): ?> <!-- Itera sobre los semestres -->
                <option value="<?php echo htmlspecialchars($row['semester']); ?>"><?php echo htmlspecialchars($row['semester']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="estado_reserva">Estado de la Reserva:</label>
        <select id="estado_reserva" name="estado_reserva"> <!-- Selector para estado de reserva -->
            <option value="">Seleccione estado</option>
            <option value="1">Activo</option>
            <option value="0">Finalizado</option>
        </select>

        <label for="cancelada">Reserva Cancelada:</label>
        <select id="cancelada" name="cancelada"> <!-- Selector para reservas canceladas -->
            <option value="">Seleccione opción</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>

        <button type="submit">Buscar</button> <!-- Botón para enviar el formulario -->
    </form>

    <!-- Mostrar resultados -->
    <table border='1' cellspacing='0' cellpadding='10'> <!-- Tabla para mostrar los resultados -->
        <tr>
            <th>ID Usuario</th>
            <th>Nombre Usuario</th>
            <th>Apellido Usuario</th>
            <th>Teléfono Usuario</th>
            <th>Correo Usuario</th>
            <th>Tipo Usuario</th>
            <th>Observaciones</th>
            <th>Sede Seleccionada</th>
            <th>Espacio Seleccionado</th>
            <th>Fecha Actual</th>
            <th>Fecha Reserva</th>
            <th>Hora de Inicio</th>
            <th>Número de Horas</th>
            <th>Número de Minutos</th>
            <th>Cantidad de Asistentes</th>
            <th>Programa</th>
            <th>Semestre</th>
            <th>Estado Reserva</th>
            <th>Reserva Cancelada</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?> <!-- Verifica si hay resultados -->
            <?php while ($row = $result->fetch_assoc()): ?> <!-- Itera sobre los resultados -->
                <tr>
                    <td><?php echo htmlspecialchars($row["id_user"]); ?></td>
                    <td><?php echo htmlspecialchars($row["name_user"]); ?></td>
                    <td><?php echo htmlspecialchars($row["surname_user"]); ?></td>
                    <td><?php echo htmlspecialchars($row["phone_user"]); ?></td>
                    <td><?php echo htmlspecialchars($row["email_user"]); ?></td>
                    <td><?php echo htmlspecialchars($row["user_type"]); ?></td>
                    <td><?php echo htmlspecialchars($row["observation"]); ?></td>
                    <td><?php echo htmlspecialchars($row["location_select"]); ?></td>
                    <td><?php echo htmlspecialchars($row["space_select"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_current"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_reserv"]); ?></td>
                    <td><?php echo htmlspecialchars($row["start_time"]); ?></td>
                    <td><?php echo htmlspecialchars($row["hours_reserv"]); ?></td>
                    <td><?php echo htmlspecialchars($row["minuts_reserv"]); ?></td>
                    <td><?php echo htmlspecialchars($row["number_attendees"]); ?></td>
                    <td><?php echo htmlspecialchars($row["program"]); ?></td>
                    <td><?php echo htmlspecialchars($row["semester"]); ?></td>
                    <td><?php echo ($row["state_reservation"] ? "Activo" : "Finalizado"); ?></td>
                    <td><?php echo ($row["cancel_reserv"] ? "Sí" : "No"); ?></td>
                    </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan='25'>No se encontraron reservas.</td></tr> <!-- Mensaje si no hay reservas -->
        <?php endif; ?>
    </table>

    <?php $conexion->close(); ?> <!-- Cierra la conexión a la base de datos -->
</body>

</html>