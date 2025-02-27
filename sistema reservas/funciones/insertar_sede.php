<?php
require_once('../conexion/config.php'); // Incluye el archivo de configuración de la base de datos
require_once('../funciones/database.php'); // Incluye el archivo de funciones de base de datos

// Configuración para mostrar errores (solo para desarrollo, no usar en producción)
ini_set('display_errors', 1); // (modificado) Muestra errores
ini_set('display_startup_errors', 1); // (modificado) Muestra errores de inicio
error_reporting(E_ALL); // (modificado) Reporta todos los errores

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombreSede = trim($_POST['nombreSede']); // (modificado) Elimina espacios en blanco al inicio y al final
    $direccion = trim($_POST['direccion']); // (modificado) Elimina espacios en blanco al inicio y al final
    $contacto = trim($_POST['contacto']); // (modificado) Elimina espacios en blanco al inicio y al final

    // Validar datos
    if (empty($nombreSede) || empty($direccion) || empty($contacto)) {
        header('Location: ../Visualizaciones/crear_sede.php?error=Por favor, completa todos los campos'); // Redirigir con mensaje de error
        exit; // (modificado) Termina la ejecución del script
    }

    // Preparar los datos para insertar
    $data = [
        'name_location' => $nombreSede, // Nombre de la sede
        'addres' => $direccion, // Dirección de la sede
        'contact' => $contacto, // Contacto de la sede
        'state_location' => 1 // Estado de la sede habilitada por defecto
    ];

    // Insertar en la tabla 'locations'
    if (insert($conexion, 'locations', $data)) { // (modificado) Llama a la función insert
        header('Location: ../Visualizaciones/sedes.php?success=Sede creada exitosamente'); // Redirigir con mensaje de éxito
    } else {
        header('Location: ../Visualizaciones/crear_sede.php?error=Error al crear la sede'); // Redirigir con mensaje de error
    }
    exit; // (modificado) Termina la ejecución del script
}
?>