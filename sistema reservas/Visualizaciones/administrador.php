<?php 
require_once('../funciones/database.php'); // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión o reanuda la sesión actual

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    header("Location: ../Visualizaciones/login.php"); // Redirige al usuario a la página de inicio de sesión si no está autenticado
    exit(); // Termina el script para evitar que se ejecute el resto del código
}

// Obtener todas las sedes
$querySedes = "SELECT * FROM locations WHERE state_location = 1"; 
$resultSedes = $conexion->query($querySedes); // Ejecuta la consulta para obtener las sedes activas

// Obtener todos los espacios habilitados
$queryEspacios = "SELECT * FROM spaces WHERE state_space = true"; 
$resultEspacios = $conexion->query($queryEspacios); // Ejecuta la consulta para obtener los espacios habilitados

// Almacenar los espacios en un array agrupado por sede
$espaciosPorSede = []; // Inicializa un array vacío para almacenar los espacios
while ($espacio = $resultEspacios->fetch_assoc()) { // Itera sobre los resultados de los espacios
    $espaciosPorSede[$espacio['id_location']][] = $espacio; // Agrupa los espacios por su ID de ubicación
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title>Sistema de Reservas | Biblioteca</title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluye scripts necesarios -->
    <link rel="stylesheet" href="../styles/styles_calendario.css"> <!-- Incluye la hoja de estilos para el calendario -->
</head>

<body>
    <?php include('../includes/header.php'); ?> <!-- Incluye el encabezado de la página -->
    
    <!-- Incluyo el formulario del calendario-->
    <?php include('../includes/modal_formulario_admin.php'); ?> <!-- Incluye el formulario para el administrador -->

    <!-- incluir el calendario -->
    <?php include('../includes/calendario.php'); ?> <!-- Incluye el calendario -->
</body>

</html>