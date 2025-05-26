<?php
// exportar_excel.php
require_once('PhpXlsxGenerator.php');

include('../conexion/config.php');

$sql = "SELECT r.*, p.nombre_programa 
        FROM reservations r 
        LEFT JOIN programas p ON r.program = p.id_programa";

// Obtener los parámetros de filtro
$filters = [];

if (!empty($_GET['tipo_usuario'])) {
    $tipo_usuario = $conexion->real_escape_string($_GET['tipo_usuario']);
    $filters[] = "user_type = '$tipo_usuario'";
}

if (!empty($_GET['sede'])) {
    $sede = $conexion->real_escape_string($_GET['sede']);
    $filters[] = "location_select = '$sede'";
    if (!empty($_GET['espacio'])) {
        $espacio = $conexion->real_escape_string($_GET['espacio']);
        $filters[] = "space_select = '$espacio'";
    }
} elseif (!empty($_GET['espacio'])) {
    $espacio = $conexion->real_escape_string($_GET['espacio']);
    $filters[] = "space_select = '$espacio'";
}

if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
    $fecha_inicio = $conexion->real_escape_string($_GET['fecha_inicio']);
    $fecha_fin = $conexion->real_escape_string($_GET['fecha_fin']);
    $filters[] = "date_reserv BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

if (!empty($_GET['programa'])) {
    $programa = $conexion->real_escape_string($_GET['programa']);
    $filters[] = "p.nombre_programa = '$programa'";
}

if (!empty($_GET['semestre'])) {
    $semestre = $conexion->real_escape_string($_GET['semestre']);
    $filters[] = "semester = '$semestre'";
}

if (isset($_GET['estado_reserva']) && $_GET['estado_reserva'] !== '') {
    $estado = $conexion->real_escape_string($_GET['estado_reserva']);
    $filters[] = "state_reservation = '$estado'";
}

if (isset($_GET['cancelada']) && $_GET['cancelada'] !== '') {
    $cancelada = $conexion->real_escape_string($_GET['cancelada']);
    $filters[] = "cancel_reserv = '$cancelada'";
}


// Construir la consulta SQL
$sql = "SELECT r.*, p.nombre_programa AS nombre_programa 
        FROM reservations r 
        LEFT JOIN programas p ON r.program = p.id_programa";
if (!empty($filters)) {
    $sql .= " WHERE ".implode(" AND ", $filters);
}

$result = $conexion->query($sql);

// Preparar datos para exportar
$datos = array(
    array(
        'ID Usuario', 'Nombre Usuario', 'Apellido Usuario', 'Teléfono Usuario',
        'Correo Usuario', 'Tipo Usuario', 'Observaciones', 'Sede Seleccionada',
        'Espacio Seleccionado', 'Fecha Actual', 'Fecha Reserva', 'Hora de Inicio',
        'Número de Horas', 'Número de Minutos', 'Cantidad de Asistentes',
        'Programa', 'Semestre', 'Estado Reserva', 'Reserva Cancelada'
    )
);

while ($row = $result->fetch_assoc()) {
    $fila = array(
        $row['id_user'],
        $row['name_user'],
        $row['surname_user'],
        $row['phone_user'],
        $row['email_user'],
        $row['user_type'],
        $row['observation'],
        $row['location_select'],
        $row['space_select'],
        $row['date_current'],
        $row['date_reserv'],
        $row['start_time'],
        $row['hours_reserv'],
        $row['minuts_reserv'],
        $row['number_attendees'],
        $row['nombre_programa'],
        $row['semester'],
        ($row['state_reservation'] ? 'Activo' : 'Finalizado'),
        ($row['cancel_reserv'] ? 'Si' : 'No')
    );
    $datos[] = $fila;
}

// Crear y descargar el archivo Excel
$xlsx = CodexWorld\PhpXlsxGenerator::fromArray($datos);
$xlsx->downloadAs('reporte_reservas_'.date('Ymd').'.xlsx');

$conexion->close();