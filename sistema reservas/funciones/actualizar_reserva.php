<?php
require_once('../conexion/config.php');

// Habilitar el registro de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = filter_var($_POST['id_reserva'], FILTER_SANITIZE_NUMBER_INT);

    // Verificación básica del ID
    if (!$idReserva || $idReserva <= 0) {
        http_response_code(400);
        echo json_encode(['estado' => 'error', 'mensaje' => 'ID de reserva inválido']);
        exit;
    }

    // Consulta para deshabilitar la reserva
    $sql = "UPDATE reservations 
            SET state_reservation = 0,
                cancel_reserv = 1
            WHERE id_reservation = ?";
    
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['estado' => 'error', 'mensaje' => 'Error al preparar la consulta']);
        exit;
    }
    
    $stmt->bind_param("i", $idReserva);
    
    if ($stmt->execute()) {
        echo json_encode(['estado' => 'success']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['estado' => 'error', 'mensaje' => 'Error al ejecutar la consulta']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Parámetros inválidos']);
}
?>
