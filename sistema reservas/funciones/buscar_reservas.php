<?php
include('../conexion/config.php'); // Incluye el archivo de configuración de la base de datos

// Recoger datos del formulario, usando el operador null coalescing para evitar errores si no están definidos
$id_user = $_POST['id_user'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$name_user = $_POST['name_user'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$surname_user = $_POST['surname_user'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$phone_user = $_POST['phone_user'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$email_user = $_POST['email_user'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$sedes = $_POST['sedes'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$espacios = $_POST['espacios'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$user_type = $_POST['user_type'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$program = $_POST['program'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$semester = $_POST['semester'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$state_reservation = $_POST['state_reservation'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos
$cancel_reserv = $_POST['cancel_reserv'] ?? ''; // (modificado) Se puede considerar validar y sanitizar estos datos

// Inicializar la consulta SQL
$query = "SELECT * FROM reserva WHERE 1=1"; // (modificado) Usar 1=1 para facilitar la concatenación de condiciones
$params = []; // Array para los parámetros de la consulta
$types = ''; // Cadena para los tipos de parámetros

// Agregar condiciones a la consulta según los datos proporcionados
if (!empty($id_user)) {
    $query .= " AND id_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$id_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($name_user)) {
    $query .= " AND name_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$name_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($surname_user)) {
    $query .= " AND surname_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$surname_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($phone_user)) {
    $query .= " AND phone_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$phone_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($email_user)) {
    $query .= " AND email_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$email_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($sedes)) {
    $query .= " AND location_select = ?"; // (modificado) Comparación exacta para sedes
    $params[] = $sedes; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($espacios)) {
    $query .= " AND space_select = ? AND location_select = ?"; // (modificado) Comparación exacta para espacios
    $params[] = $espacios; // (modificado) Se puede considerar sanitizar este valor
    $params[] = $sedes; // (modificado) Se puede considerar sanitizar este valor
    $types .= 'ss'; // Tipo de dato: string, string
}
if (!empty($user_type)) {
    $query .= " AND user_type = ?"; // (modificado) Comparación exacta para
    $user_type = $_POST['user_type'] ?? ''; // (modificado) Se puede considerar validar y sanitizar este dato
$program = $_POST['program'] ?? ''; // (modificado) Se puede considerar validar y sanitizar este dato
$semester = $_POST['semester'] ?? ''; // (modificado) Se puede considerar validar y sanitizar este dato
$state_reservation = $_POST['state_reservation'] ?? ''; // (modificado) Se puede considerar validar y sanitizar este dato
$cancel_reserv = $_POST['cancel_reserv'] ?? ''; // (modificado) Se puede considerar validar y sanitizar este dato
}
// Inicializar la consulta SQL
$query = "SELECT * FROM reserva WHERE 1=1"; // (modificado) Usar 1=1 para facilitar la concatenación de condiciones
$params = []; // Array para los parámetros de la consulta
$types = ''; // Cadena para los tipos de parámetros

// Agregar condiciones a la consulta según los datos proporcionados
if (!empty($id_user)) {
    $query .= " AND id_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$id_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($name_user)) {
    $query .= " AND name_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$name_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($surname_user)) {
    $query .= " AND surname_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$surname_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($phone_user)) {
    $query .= " AND phone_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$phone_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($email_user)) {
    $query .= " AND email_user LIKE ?"; // (modificado) Usar LIKE para permitir búsquedas parciales
    $params[] = "%$email_user%"; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($sedes)) {
    $query .= " AND location_select = ?"; // (modificado) Comparación exacta para sedes
    $params[] = $sedes; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($espacios)) {
    $query .= " AND space_select = ? AND location_select = ?"; // (modificado) Comparación exacta para espacios
    $params[] = $espacios; // (modificado) Se puede considerar sanitizar este valor
    $params[] = $sedes; // (modificado) Se puede considerar sanitizar este valor
    $types .= 'ss'; // Tipo de dato: string, string
}
if (!empty($user_type)) {
    $query .= " AND user_type = ?"; // (modificado) Comparación exacta para tipo de usuario
    $params[] = $user_type; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($program)) {
    $query .= " AND program = ?"; // (modificado) Comparación exacta para programa
    $params[] = $program; // (modificado) Se puede considerar sanitizar este valor
    $types .= 's'; // Tipo de dato: string
}
if (!empty($semester)) {
    $query .= " AND semester = ?"; // (modificado) Comparación exacta para semestre
    $params[] = $semester; // (modificado) Se puede considerar sanitizar este valor
    $types .= 'i'; // Tipo de dato: integer
}
if ($state_reservation !== '') {
    $query .= " AND state_reservation = ?"; // (modificado) Comparación exacta para estado de reserva
    $params[] = $state_reservation; // (modificado) Se puede considerar sanitizar este valor
    $types .= 'i'; // Tipo de dato: integer
}
if ($cancel_reserv !== '') {
    $query .= " AND cancel_reserv = ?"; // (modificado) Comparación exacta para cancelación de reserva
    $params[] = $cancel_reserv; // (modificado) Se puede considerar sanitizar este valor
    $types .= 'i'; // Tipo de dato: integer
}

// Preparar y ejecutar la consulta
$stmt = $conexion->prepare($query); // (modificado) Preparar la consulta SQL
$stmt->bind_param($types, ...$params); // (modificado) Vincular parámetros a la consulta
$stmt->execute(); // (modificado) Ejecutar la consulta
$result = $stmt->get_result(); // (modificado) Obtener el resultado de la consulta

$reservas = $result->fetch_all(MYSQLI_ASSOC); // (modificado) Obtener todas las reservas como un array asociativo
echo json_encode($reservas); // (modificado) Devolver el resultado en formato JSON
?>