<?php
require_once('../conexion/config.php');

// Obtener la sede y espacio seleccionados (si se proporcionan)
$sede_id = isset($_GET['sede']) ? $_GET['sede'] : null;
$espacio_id = isset($_GET['espacio']) ? $_GET['espacio'] : null;

// Obtener el nombre del espacio seleccionado
$nombre_espacio = null;
if ($espacio_id) {
    $sql_nombre = "SELECT name_space FROM spaces WHERE id_space = ?";
    $stmt_nombre = $conexion->prepare($sql_nombre);
    $stmt_nombre->bind_param("i", $espacio_id);
    $stmt_nombre->execute();
    $result_nombre = $stmt_nombre->get_result();
    if ($row_nombre = $result_nombre->fetch_assoc()) {
        $nombre_espacio = $row_nombre['name_space'];
    }
}

// Obtener reservas confirmadas desde la fecha actual en adelante
$sql = "SELECT r.id_reservation, r.date_reserv, r.start_time, r.hours_reserv, r.minuts_reserv, l.name_location, s.name_space 
        FROM reservations r
        JOIN locations l ON r.id_location = l.id_location
        JOIN spaces s ON r.id_space = s.id_space
        WHERE r.date_reserv >= CURDATE() 
        AND l.id_location = ? 
        AND s.id_space = ?
        GROUP BY r.date_reserv, r.start_time";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $sede_id, $espacio_id);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];
$reservados = [];

// Procesar las reservas existentes
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hora_inicio = $row['start_time'];
        $horas = $row['hours_reserv'];
        $minutos = $row['minuts_reserv'];

        // Calcular la hora de finalización de la reserva
        $hora_fin = date("H:i:s", strtotime("+$horas hours +$minutos minutes", strtotime($hora_inicio)));

        // Guardar las reservas ocupadas
        $reservados[] = [
            'fecha' => $row['date_reserv'],
            'inicio' => $hora_inicio,
            'fin' => $hora_fin,
            'sede' => $row['name_location'],
            'espacio' => $row['name_space']
        ];

        // Evento de reserva ocupada
        $eventos[] = [
            'title' => 'Reservado',
            'start' => $row['date_reserv'] . 'T' . $hora_inicio,
            'end' => $row['date_reserv'] . 'T' . $hora_fin,
            'color' => '#ff0000', // Rojo para reservas ocupadas
            'display' => 'block',
            'extendedProps' => [
                'tipo' => 'reserva',
                'sede' => $row['name_location'],
                'espacio' => $row['name_space']
            ]
        ];
    }
}

// Definir intervalos fijos de disponibilidad
$intervalos = [
    ["06:30:00", "10:00:00"],
    ["10:00:00", "13:30:00"],
    ["13:30:00", "17:00:00"],
    ["17:00:00", "21:00:00"]
];

// Obtener la fecha de hoy
$fecha_actual = date("Y-m-d");

// Generar disponibilidad respetando los intervalos y reservas
for ($dia = 0; $dia < 7; $dia++) {
    $fecha_dia = date("Y-m-d", strtotime("+$dia days", strtotime($fecha_actual)));

    // Obtener todas las reservas del día actual
    $reservas_del_dia = array_filter($reservados, function ($reserva) use ($fecha_dia) {
        return $reserva['fecha'] === $fecha_dia;
    });

    // Ordenar reservas por hora de inicio
    usort($reservas_del_dia, function ($a, $b) {
        return strcmp($a['inicio'], $b['inicio']);
    });

    // Procesar cada intervalo de tiempo
    foreach ($intervalos as $intervalo) {
        $inicio_intervalo = $intervalo[0];
        $fin_intervalo = $intervalo[1];

        // Verificar si hay reservas en el intervalo
        $tiene_reserva = false;
        foreach ($reservas_del_dia as $reserva) {
            if ($reserva['inicio'] < $fin_intervalo && $reserva['fin'] > $inicio_intervalo) {
                $tiene_reserva = true;
                break;
            }
        }

        // Si no hay reservas en el intervalo, mostrar disponibilidad
        if (!$tiene_reserva) {
            $eventos[] = [
                'title' => $nombre_espacio, // Solo mostrar el nombre del espacio
                'start' => "$fecha_dia" . "T" . $inicio_intervalo,
                'end' => "$fecha_dia" . "T" . $fin_intervalo,
                'color' => '#008000', // Verde para disponibilidad
                'display' => 'block',
                'extendedProps' => [
                    'tipo' => 'disponibilidad',
                    'sede' => $sede_id,
                    'espacio' => $espacio_id
                ]
            ];
        }
    }
}

// Establecer el tipo de contenido a JSON y devolver los eventos
header('Content-Type: application/json');
echo json_encode($eventos);
?>