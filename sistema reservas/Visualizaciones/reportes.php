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
// Consultas para los selectores
$tipos_usuario = $conexion->query("SELECT DISTINCT user_type FROM reservations");
$sedes = $conexion->query("SELECT DISTINCT location_select FROM reservations");
$espacios = $conexion->query("SELECT DISTINCT space_select FROM reservations");
$programas = $conexion->query("SELECT * FROM programas");
$semestres = $conexion->query("SELECT DISTINCT semester FROM reservations");

// Construir consulta base con JOIN para obtener nombre del programa
$sql = "SELECT r.*, p.nombre_programa AS nombre_programa 
        FROM reservations r 
        LEFT JOIN programas p ON r.program = p.id_programa";


$filters = [];

function addFilter($condition) {
    global $filters;
    $filters[] = $condition;
}

// Aplicar filtros
if (!empty($_GET['tipo_usuario'])) {
    $tipo_usuario = $conexion->real_escape_string($_GET['tipo_usuario']);
    addFilter("r.user_type = '$tipo_usuario'");
}

if (!empty($_GET['sede'])) {
    $sede = $conexion->real_escape_string($_GET['sede']);
    addFilter("r.location_select = '$sede'");

    if (!empty($_GET['espacio'])) {
        $espacio = $conexion->real_escape_string($_GET['espacio']);
        addFilter("r.space_select = '$espacio'");
    }
} elseif (!empty($_GET['espacio'])) {
    $espacio = $conexion->real_escape_string($_GET['espacio']);
    addFilter("r.space_select = '$espacio'");
}

if (!empty($_GET['fecha_inicio'])) {
    $fecha_inicio = $conexion->real_escape_string($_GET['fecha_inicio']);
    addFilter("r.date_current >= '$fecha_inicio'");
}

if (!empty($_GET['fecha_fin'])) {
    $fecha_fin = $conexion->real_escape_string($_GET['fecha_fin']);
    addFilter("r.date_reserv <= '$fecha_fin'");
}

if (!empty($_GET['programa'])) {
    $programa = $conexion->real_escape_string($_GET['programa']);
    addFilter("p.nombre_programa = '$programa'");
}

if (!empty($_GET['semestre'])) {
    $semestre = $conexion->real_escape_string($_GET['semestre']);
    addFilter("r.semester = '$semestre'");
}

if (!empty($_GET['estado_reserva'])) {
    $estado_reserva = $conexion->real_escape_string($_GET['estado_reserva']);
    addFilter("r.state_reservation = '$estado_reserva'");
}

if (!empty($_GET['cancelada'])) {
    $cancelada = $conexion->real_escape_string($_GET['cancelada']);
    addFilter("r.cancel_reserv = '$cancelada'");
}

if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters);
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
<?php include('../includes/header.php'); ?>
    <h1>Reportes</h1>

    <!-- Botón principal de filtrado con diseño mejorado -->
    <div class="filter-container">
        <button id="toggleFilters" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filtrar Reportes
        </button>
        
        <!-- Contenedor de filtros (oculto inicialmente) -->
        <div id="filtersPanel" class="filters-panel">
            <form method="GET" action="reportes.php" class="advanced-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="tipo_usuario">Tipo de Usuario:</label>
                        <select id="tipo_usuario" name="tipo_usuario" class="form-select">
                            <option value="">Seleccione tipo</option>
                            <?php while ($row = $tipos_usuario->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['user_type']); ?>">
                                    <?php echo htmlspecialchars($row['user_type']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sede">Sede:</label>
                        <select id="sede" name="sede" onchange="actualizarEspacios()" class="form-select">
                            <option value="">Seleccione sede</option>
                            <?php while ($row = $sedes->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['location_select']); ?>">
                                    <?php echo htmlspecialchars($row['location_select']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="espacio">Espacio:</label>
                        <select id="espacio" name="espacio" class="form-select">
                            <option value="">Seleccione un espacio</option>
                            <?php while ($row = $espacios->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['space_select']); ?>">
                                    <?php echo htmlspecialchars($row['space_select']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control">
                    </div>
                    
                    <div class="filter-group">
                        <label for="fecha_fin">Fecha Fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                    </div>
                    
                    <div class="filter-group">
                        <label for="programa">Programa:</label>
                        <select id="programa" name="programa" class="form-select">
                            <option value="">Seleccione programa</option>
                            <?php while ($row = $programas->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['nombre_programa']); ?>">
                                    <?php echo htmlspecialchars($row['nombre_programa']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="semestre">Semestre:</label>
                        <select id="semestre" name="semestre" class="form-select">
                            <option value="">Seleccione semestre</option>
                            <?php while ($row = $semestres->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['semester']); ?>">
                                    <?php echo htmlspecialchars($row['semester']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="estado_reserva">Estado Reserva:</label>
                        <select id="estado_reserva" name="estado_reserva" class="form-select">
                            <option value="">Seleccione estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Finalizado</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="cancelada">Reserva Cancelada:</label>
                        <select id="cancelada" name="cancelada" class="form-select">
                            <option value="">Seleccione opción</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                    <button type="button" id="resetFilters" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Limpiar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
<div class="export-actions">
    <!-- Botón de Exportar a PDF -->
    <form method="GET" action="exportar_pdf.php" class="export-form">
        <input type="hidden" name="tipo_usuario" value="<?= htmlspecialchars($_GET['tipo_usuario'] ?? '') ?>">
        <input type="hidden" name="sede" value="<?= htmlspecialchars($_GET['sede'] ?? '') ?>">
        <input type="hidden" name="espacio" value="<?= htmlspecialchars($_GET['espacio'] ?? '') ?>">
        <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
        <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
        <input type="hidden" name="programa" value="<?= htmlspecialchars($_GET['programa'] ?? '') ?>">
        <input type="hidden" name="semestre" value="<?= htmlspecialchars($_GET['semestre'] ?? '') ?>">
        <input type="hidden" name="estado_reserva" value="<?= htmlspecialchars($_GET['estado_reserva'] ?? '') ?>">
        <input type="hidden" name="cancelada" value="<?= htmlspecialchars($_GET['cancelada'] ?? '') ?>">
        <button type="submit" class="btn btn-export btn-pdf">
            <i class="fas fa-file-pdf"></i> Exportar a PDF
        </button>
    </form>

    <!-- Botón de Exportar a Excel -->
    <form method="GET" action="exportar_excel.php" class="export-form">
        <input type="hidden" name="tipo_usuario" value="<?= htmlspecialchars($_GET['tipo_usuario'] ?? '') ?>">
        <input type="hidden" name="sede" value="<?= htmlspecialchars($_GET['sede'] ?? '') ?>">
        <input type="hidden" name="espacio" value="<?= htmlspecialchars($_GET['espacio'] ?? '') ?>">
        <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
        <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
        <input type="hidden" name="programa" value="<?= htmlspecialchars($_GET['programa'] ?? '') ?>">
        <input type="hidden" name="semestre" value="<?= htmlspecialchars($_GET['semestre'] ?? '') ?>">
        <input type="hidden" name="estado_reserva" value="<?= htmlspecialchars($_GET['estado_reserva'] ?? '') ?>">
        <input type="hidden" name="cancelada" value="<?= htmlspecialchars($_GET['cancelada'] ?? '') ?>">
        <button type="submit" class="btn btn-export btn-excel">
            <i class="fas fa-file-excel"></i> Exportar a Excel
        </button>
    </form>
</div>

<link rel="stylesheet" href="../styles/reporte.css">
<script src='../scripts/reporte.js'></script>

    <!-- Mostrar resultados -->
    <table border='1' cellspacing='0' cellpadding='10'>
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

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["id_user"]) ?></td>
                    <td><?= htmlspecialchars($row["name_user"]) ?></td>
                    <td><?= htmlspecialchars($row["surname_user"]) ?></td>
                    <td><?= htmlspecialchars($row["phone_user"]) ?></td>
                    <td><?= htmlspecialchars($row["email_user"]) ?></td>
                    <td><?= htmlspecialchars($row["user_type"]) ?></td>
                    <td><?= htmlspecialchars($row["observation"]) ?></td>
                    <td><?= htmlspecialchars($row["location_select"]) ?></td>
                    <td><?= htmlspecialchars($row["space_select"]) ?></td>
                    <td><?= htmlspecialchars($row["date_current"]) ?></td>
                    <td><?= htmlspecialchars($row["date_reserv"]) ?></td>
                    <td><?= htmlspecialchars($row["start_time"]) ?></td>
                    <td><?= htmlspecialchars($row["hours_reserv"]) ?></td>
                    <td><?= htmlspecialchars($row["minuts_reserv"]) ?></td>
                    <td><?= htmlspecialchars($row["number_attendees"]) ?></td>
                    <td><?php echo htmlspecialchars($row["nombre_programa"] ?? 'Sin asignar'); ?></td>
                    <td><?= htmlspecialchars($row["semester"]) ?></td>
                    <td><?= ($row["state_reservation"] ? "Activo" : "Finalizado") ?></td>
                    <td><?= ($row["cancel_reserv"] ? "Sí" : "No") ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan='19'>No se encontraron reservas.</td></tr>
        <?php endif; ?>
    </table>

    <?php $conexion->close(); ?>
</body>
</html>
