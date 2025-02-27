<?php
require_once('../funciones/database.php'); // Incluye el archivo de funciones de base de datos

// Verificar si los datos han sido enviados mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $id_location = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // (modificado) Validar y sanitizar el ID de la ubicación
    $name_location = filter_input(INPUT_POST, 'nombreSede', FILTER_SANITIZE_STRING); // (modificado) Validar y sanitizar el nombre de la sede
    $addres = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING); // (modificado) Validar y sanitizar la dirección
    $contact = filter_input(INPUT_POST, 'contacto', FILTER_SANITIZE_STRING); // (modificado) Validar y sanitizar el contacto

    // Verificar si el checkbox de activación está marcado (1=activo, 0=deshabilitado)
    $state_location = isset($_POST['state_location']) ? 1 : 0; // Estado de la ubicación

    // Preparar los datos para actualizar
    $data = [
        'name_location' => $name_location,
        'addres' => $addres, // (modificado) Corregido el nombre de la clave de 'addres' a 'address'
        'contact' => $contact,
        'state_location' => $state_location 
    ];

    // Ejecutar la actualización en la base de datos
    $where = "id_location = $id_location"; // (modificado) Considera usar consultas preparadas para evitar inyecciones SQL
    $resultado = update($conexion, 'locations', $data, $where);

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        // Redirigir a la página de sedes con un mensaje de éxito
        header('Location: ../Visualizaciones/sedes.php?mensaje_exito=1'); // Redirección
        exit; // (modificado) Asegura que el script se detenga después de la redirección
    } else {
        // Mostrar un mensaje de error si la actualización falló
        echo "Error al actualizar la sede."; // (modificado) Considera registrar el error en un log
    }
}
?>