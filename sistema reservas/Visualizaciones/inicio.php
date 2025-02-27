<?php
require_once('../funciones/database.php'); // Incluye el archivo de funciones de base de datos

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
    <?php include('../includes/modal_formulario.php'); ?> <!-- Incluye el formulario para el calendario -->

    <!-- incluir el calendario -->
    <?php include('../includes/calendario.php'); ?> <!-- Incluye el calendario -->
    
    <div id="errorBanner" style="display:none; color:red;"></div> <!-- Banner de error oculto por defecto -->
</body>

</html>