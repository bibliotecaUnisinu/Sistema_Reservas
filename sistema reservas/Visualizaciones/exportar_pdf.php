<?php
// Incluir la conexión a la base de datos
include('../conexion/config.php');
require('../fpdf/fpdf.php');

// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin_name'])) {
    header("Location: ../Visualizaciones/login.php");
    exit();
}

// Función para construir la consulta SQL con filtros
function buildFilteredQuery($conexion) {
    $sql = "SELECT r.*, p.nombre_programa AS nombre_programa 
        FROM reservations r 
        LEFT JOIN programas p ON r.program = p.id_programa";
    $filters = [];
    
    // Filtrar por tipo de usuario
    if (!empty($_GET['tipo_usuario'])) {
        $tipo_usuario = $conexion->real_escape_string($_GET['tipo_usuario']);
        $filters[] = "user_type = '$tipo_usuario'";
    }
    
    // Filtrar por sede y espacio
    if (!empty($_GET['sede']) || !empty($_GET['espacio'])) {
        if (!empty($_GET['sede'])) {
            $sede = $conexion->real_escape_string($_GET['sede']);
            $filters[] = "location_select = '$sede'";
        }
        
        if (!empty($_GET['espacio'])) {
            $espacio = $conexion->real_escape_string($_GET['espacio']);
            $filters[] = "space_select = '$espacio'";
        }
    }
    
    // Filtrar por rango de fechas
    if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
        $fecha_inicio = $conexion->real_escape_string($_GET['fecha_inicio']);
        $fecha_fin = $conexion->real_escape_string($_GET['fecha_fin']);
        $filters[] = "date_reserv BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }
    
    // Filtrar por programa
    if (!empty($_GET['programa'])) {
    $programa = $conexion->real_escape_string($_GET['programa']);
    $filters[] = "p.nombre_programa = '$programa'";
    }
    
    // Filtrar por semestre
    if (!empty($_GET['semestre'])) {
        $semestre = $conexion->real_escape_string($_GET['semestre']);
        $filters[] = "semester = '$semestre'";
    }
    
    // Filtrar por estado de la reserva
    if (!empty($_GET['estado_reserva'])) {
        $estado_reserva = $conexion->real_escape_string($_GET['estado_reserva']);
        $filters[] = "state_reservation = '$estado_reserva'";
    }
    
    // Filtrar por cancelación de la reserva
    if (!empty($_GET['cancelada'])) {
        $cancelada = $conexion->real_escape_string($_GET['cancelada']);
        $filters[] = "cancel_reserv = '$cancelada'";
    }
    
    // Agregar los filtros a la consulta si existen
    if (!empty($filters)) {
        $sql .= " WHERE " . implode(" AND ", $filters);
    }
    
    return $sql;
}

// Construir y ejecutar la consulta con filtros
$sql = buildFilteredQuery($conexion);
$result = $conexion->query($sql);

// Crear PDF
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 8);

// Título del reporte
$pdf->Cell(275, 8, 'Reporte de Reservas', 0, 1, 'C');
$pdf->Ln(5);

// Definir ancho de las columnas
$columnWidths = [12, 20, 20, 18, 35, 20, 20, 20, 20, 18, 20, 18, 15, 15, 18, 20, 15, 18, 18];

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 7);
$headers = [
    'ID', 'Nombre', 'Apellido', 'Teléfono', 'Correo', 'Tipo', 'Obs.',
    'Sede', 'Espacio', 'F. Actual', 'F. Reserva', 'Hora Ini.', 'Horas', 'Min.',
    'Asist.', 'Programa', 'Sem.', 'Estado', 'Cancel.'
];
foreach ($headers as $index => $header) {
    $pdf->Cell($columnWidths[$index], 7, utf8_decode($header), 1, 0, 'C');
}
$pdf->Ln();

// Llenado de la tabla con datos filtrados
$pdf->SetFont('Arial', '', 7);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell($columnWidths[0], 7, $row['id_user'], 1, 0, 'C');
    $pdf->Cell($columnWidths[1], 7, utf8_decode($row['name_user']), 1, 0, 'C');
    $pdf->Cell($columnWidths[2], 7, utf8_decode($row['surname_user']), 1, 0, 'C');
    $pdf->Cell($columnWidths[3], 7, $row['phone_user'], 1, 0, 'C');
    $pdf->Cell($columnWidths[4], 7, utf8_decode($row['email_user']), 1, 0, 'C');
    $pdf->Cell($columnWidths[5], 7, utf8_decode($row['user_type']), 1, 0, 'C');
    $pdf->Cell($columnWidths[6], 7, utf8_decode($row['observation']), 1, 0, 'C');
    $pdf->Cell($columnWidths[7], 7, utf8_decode($row['location_select']), 1, 0, 'C');
    $pdf->Cell($columnWidths[8], 7, utf8_decode($row['space_select']), 1, 0, 'C');
    $pdf->Cell($columnWidths[9], 7, $row['date_current'], 1, 0, 'C');
    $pdf->Cell($columnWidths[10], 7, $row['date_reserv'], 1, 0, 'C');
    $pdf->Cell($columnWidths[11], 7, $row['start_time'], 1, 0, 'C');
    $pdf->Cell($columnWidths[12], 7, $row['hours_reserv'], 1, 0, 'C');
    $pdf->Cell($columnWidths[13], 7, $row['minuts_reserv'], 1, 0, 'C');
    $pdf->Cell($columnWidths[14], 7, $row['number_attendees'], 1, 0, 'C');
    $pdf->Cell($columnWidths[15], 7, utf8_decode($row['nombre_programa']), 1, 0, 'C');
    $pdf->Cell($columnWidths[16], 7, $row['semester'], 1, 0, 'C');
    $pdf->Cell($columnWidths[17], 7, ($row['state_reservation'] ? "Activo" : "Finalizado"), 1, 0, 'C');
    $pdf->Cell($columnWidths[18], 7, ($row['cancel_reserv'] ? "Si" : "No"), 1, 1, 'C');
}

// Salida del PDF
$pdf->Output('D', 'reporte_reservas_filtrado.pdf');