<?php
require_once('../conexion/config.php');

// Manejar el envío POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $datos = $_POST;

    // Validaciones básicas
    if (!isset($datos['id_reservation']) || empty($datos['id_reservation'])) {
        http_response_code(400);
        echo json_encode(['estado' => 'error', 'mensaje' => 'ID de reserva no válido']);
        exit;
    }

    // Validar que no sean ambos 0
    if ($datos['horas_reserva'] === 0 && $datos['minutos_reserva'] === 0) {
        http_response_code(400);
        echo json_encode(['estado' => 'error', 'mensaje' => 'No puede seleccionar 0 horas y 0 minutos']);
        exit;
    }

    try {
        // Preparar la consulta para actualizar la reserva
        $sql = "UPDATE reservations 
                SET date_reserv = ?, 
                    start_time = ?, 
                    hours_reserv = ?, 
                    minuts_reserv = ? 
                WHERE id_reservation = ?";
        
        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta");
        }
        
        // Vincular los parámetros
        $stmt->bind_param("ssiii",
            $datos['fecha_inicio'],   // date_reserv
            $datos['hora_inicio'],     // start_time
            $datos['horas_reserva'],   // hours_reserv
            $datos['minutos_reserva'], // minuts_reserv
            $datos['id_reservation']    // id_reservation
        );
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['estado' => 'success']);
        } else {
            throw new Exception("Error al ejecutar la consulta");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['estado' => 'error', 'mensaje' => 'Ocurrió un error al modificar la reserva: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Método no permitido']);
}
?>