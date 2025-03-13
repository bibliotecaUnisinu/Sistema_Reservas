<?php
header('Content-Type: application/json');

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents('php://input'), true);
$idReservation = $data['id_reservation'] ?? null;

// Validar que se recibió un ID válido
if (!$idReservation) {
    echo json_encode(['success' => false, 'error' => 'ID de reserva no válido']);
    exit;
}

// Conectar a la base de datos
$host = 'localhost:3306';
$user = 'root';
$password = '';
$db = 'reservas_biblioteca';

$conexion = new mysqli($host, $user, $password, $db);

if ($conexion->connect_error) {
    die(json_encode(['success' => false, 'error' => "Error de conexión: " . $conexion->connect_error]));
}

// Actualizar el estado de la reserva a "deshabilitado"
$sql = "UPDATE reservas SET cancel_reserv = 1 WHERE id_reservation = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idReservation);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
