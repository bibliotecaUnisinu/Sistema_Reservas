<?php


require_once('../funciones/database.php'); // Incluye el archivo de funciones de base de datos

// Recibir datos del formulario
$id_space = filter_input(INPUT_POST, 'id_space', FILTER_SANITIZE_NUMBER_INT); // (modificado) Validar y sanitizar el ID del espacio
$name_space = filter_input(INPUT_POST, 'name_space', FILTER_SANITIZE_STRING); // (modificado) Validar y sanitizar el nombre del espacio
$capacity = filter_input(INPUT_POST, 'capacity', FILTER_SANITIZE_NUMBER_INT); // (modificado) Validar y sanitizar la capacidad

// Si el checkbox está marcado, el espacio está activo (1); si no, está deshabilitado (0).
$state_space = isset($_POST['state_space']) ? 1 : 0; // Estado del espacio

// Preparar los datos para la actualización
$data = [
    'name_space' => $name_space,
    'capacity' => $capacity,
    'state_space' => $state_space,  
];

$where = ['id_space' => $id_space]; // Condición para la actualización

// Ejecutar la actualización
$updated = update($conexion, 'spaces', $data, $where); // Llama a la función de actualización

if ($updated) {
    // Redirigir de vuelta a la vista de espacios si se actualizó correctamente
    header('Location: ../Visualizaciones/espacios.php'); // Redirección
    exit; // (modificado) Asegura que el script se detenga después de la redirección
} else {
    // Mensaje de error más detallado
    echo "Error al actualizar el espacio."; // (modificado) Considera registrar el error en un log
}
?>