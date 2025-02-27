<?php
// obtener_reserva.php
error_reporting(E_ALL); // Reporta todos los errores
ini_set('display_errors', 1); // Muestra errores en la pantalla
include '../conexion/config.php'; // Incluir el archivo de configuración de la base de datos

session_start(); // Iniciar sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $idReserva = $_POST['id_reservation'] ?? null;
    $idLocation = $_POST['id_location'] ?? null; 
    $idSpace = $_POST['id_space'] ?? null; 
    $startTime = $_POST['start_time'] ?? null; // Hora de inicio
    $endTime = $_POST['end_time'] ?? null; // Hora de fin

    // Validar que se proporcionen el ID de reserva, ubicación, espacio y horarios
    if (empty($idReserva) || empty($idLocation) || empty($idSpace) || empty($startTime) || empty($endTime)) {
        echo json_encode(['error' => 'ID de reserva, ubicación, espacio, hora de inicio o fin no especificados.']);
        exit;
    }

    // Verificar disponibilidad del espacio solo en la misma sede
    $stmt = $conexion->prepare("SELECT * FROM reservations WHERE id_space = ? AND id_location = ? AND 
        ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))");
    $stmt->bind_param("iissss", $idSpace, $idLocation, $endTime, $startTime, $startTime, $endTime);
    $stmt->execute();
    $overlap = $stmt->get_result()->fetch_assoc();

    if ($overlap) {
        echo json_encode(['error' => 'El espacio ya está reservado en ese horario.']); // Mensaje si hay superposición
        exit;
    }

    // Preparar la consulta para obtener la reserva
    $stmt = $conexion->prepare("SELECT id_reservation, id_location, id_space, * FROM reservations WHERE id_reservation = ? AND id_location = ? AND id_space = ?");
    $stmt->bind_param("iii", $idReserva, $idLocation, $idSpace);
    $stmt->execute();
    $reserva = $stmt->get_result()->fetch_assoc(); // Obtiene la reserva como un array asociativo

    if ($reserva) { // Verifica si se encontró la reserva
        // Obtener las visitas relacionadas con la reserva
        $stmt = $conexion->prepare("SELECT * FROM visits WHERE id_reservation = ?");
        $stmt->bind_param("i", $idReserva); // Asegúrate de usar bind_param para evitar SQL Injection
        $stmt->execute();
        $visitas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Obtener todas las visitas relacionadas

        // Crear un array con la reserva y las visitas
        $resultado = [
            'reserva' => $reserva,
            'visitas' => $visitas,
            'id_location' => $reserva['id_location'], // Incluye el ID de la ubicación
            'id_space' => $reserva['id_space'] // Incluye el ID del espacio
        ];

        echo json_encode($resultado); // Devuelve la reserva, las visitas y los IDs en formato JSON
    } else {
        echo json_encode(['error' => 'Reserva no encontrada']); // Mensaje de error si no se encuentra la reserva
    }
} else {
    echo json_encode(['error' => 'Método no permitido']); // Mensaje de error si no es un POST
}
?>
