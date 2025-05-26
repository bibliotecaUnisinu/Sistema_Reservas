<?php
require_once('../conexion/config.php');

// Obtener sede y espacio seleccionados
$sede_id = isset($_GET['sede']) ? $_GET['sede'] : null;
$espacio_id = isset($_GET['espacio']) ? $_GET['espacio'] : null;

// Obtener nombre del espacio
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

// Obtener reservas confirmadas
$sql = "SELECT r.id_reservation, r.date_reserv, r.start_time, r.hours_reserv, r.minuts_reserv, 
               l.name_location, s.name_space
        FROM reservations r
        JOIN locations l ON r.id_location = l.id_location
        JOIN spaces s ON r.id_space = s.id_space
        WHERE r.date_reserv >= CURDATE() 
        AND l.id_location = ? 
        AND s.id_space = ? 
        AND r.state_reservation = 1 
        AND r.cancel_reserv = 0
        GROUP BY r.date_reserv, r.start_time";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $sede_id, $espacio_id);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];
$reservados = [];

// Procesar reservas
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hora_inicio = $row['start_time'];
        $horas = $row['hours_reserv'];
        $minutos = $row['minuts_reserv'];
        $hora_fin = date("H:i:s", strtotime("+$horas hours +$minutos minutes", strtotime($hora_inicio)));

        $reservados[] = [
            'fecha' => $row['date_reserv'],
            'inicio' => $hora_inicio,
            'fin' => $hora_fin,
            'sede' => $row['name_location'],
            'espacio' => $row['name_space']
        ];

        $eventos[] = [
            'title' => 'Reservado',
            'start' => $row['date_reserv'] . 'T' . $hora_inicio,
            'end' => $row['date_reserv'] . 'T' . $hora_fin,
            'color' => '#ff0000',
            'display' => 'block',
            'extendedProps' => [
                'tipo' => 'reserva',
                'id_reservation' => $row['id_reservation'],
                'sede' => $row['name_location'],
                'espacio' => $row['name_space']
            ]
        ];
    }
}

// Funciones para bloques dinámicos de 30 minutos
function generarIntervalos30min($inicio, $fin) {
    $intervalos = [];
    $inicio_dt = new DateTime($inicio);
    $fin_dt = new DateTime($fin);
    
    while ($inicio_dt < $fin_dt) {
        $fin_intervalo = clone $inicio_dt;
        $fin_intervalo->modify('+30 minutes');
        if ($fin_intervalo > $fin_dt) break;

        $intervalos[] = [
            'inicio' => $inicio_dt->format('H:i:s'),
            'fin' => $fin_intervalo->format('H:i:s')
        ];
        $inicio_dt->modify('+30 minutes');
    }
    return $intervalos;
}

function estaLibre($inicio, $fin, $reservas_del_dia) {
    foreach ($reservas_del_dia as $reserva) {
        if ($reserva['inicio'] < $fin && $reserva['fin'] > $inicio) {
            return false;
        }
    }
    return true;
}

function agruparIntervalos($libres) {
    $agrupados = [];
    $grupo_actual = [];

    foreach ($libres as $bloque) {
        if (empty($grupo_actual)) {
            $grupo_actual[] = $bloque;
        } else {
            $ultimo = end($grupo_actual);
            if ($ultimo['fin'] === $bloque['inicio']) {
                $grupo_actual[] = $bloque;
            } else {
                $agrupados[] = [
                    'inicio' => $grupo_actual[0]['inicio'],
                    'fin' => end($grupo_actual)['fin']
                ];
                $grupo_actual = [$bloque];
            }
        }
    }

    if (!empty($grupo_actual)) {
        $agrupados[] = [
            'inicio' => $grupo_actual[0]['inicio'],
            'fin' => end($grupo_actual)['fin']
        ];
    }

    return $agrupados;
}

// Bloques fijos base
$bloques_fijos = [
    ["06:30:00", "10:00:00"],
    ["10:00:00", "13:30:00"],
    ["13:30:00", "17:00:00"],
    ["17:00:00", "21:00:00"]
];

// Generar eventos de disponibilidad dinámica
$fecha_actual = date("Y-m-d");

for ($dia = 0; $dia < 30; $dia++) {
    $fecha_dia = date("Y-m-d", strtotime("+$dia days", strtotime($fecha_actual)));
    $dia_semana = date("N", strtotime($fecha_dia));
    if ($dia_semana == 7) continue;

    $reservas_del_dia = array_filter($reservados, function ($r) use ($fecha_dia) {
        return $r['fecha'] === $fecha_dia;
    });

    usort($reservas_del_dia, function ($a, $b) {
        return strcmp($a['inicio'], $b['inicio']);
    });

    foreach ($bloques_fijos as $bloque) {
        $subintervalos = generarIntervalos30min($bloque[0], $bloque[1]);
        $disponibles = [];

        foreach ($subintervalos as $sub) {
            if (estaLibre($sub['inicio'], $sub['fin'], $reservas_del_dia)) {
                $disponibles[] = $sub;
            }
        }

        $agrupados = agruparIntervalos($disponibles);

        foreach ($agrupados as $libre) {
            $eventos[] = [
                'title' => $nombre_espacio,
                'start' => "$fecha_dia" . "T" . $libre['inicio'],
                'end' => "$fecha_dia" . "T" . $libre['fin'],
                'color' => '#008000',
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

header('Content-Type: application/json');
echo json_encode($eventos);
?>

