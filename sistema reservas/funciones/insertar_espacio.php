<?php
require_once('../conexion/config.php'); // Incluye el archivo de configuración de la base de datos
require_once '../funciones/database.php'; // Incluye el archivo de funciones de base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica si la solicitud es de tipo POST
    $nombreEspacio = trim($_POST['nombreEspacio']); // (modificado) Elimina espacios en blanco al inicio y al final
    $capacidad = trim($_POST['capacidad']); // (modificado) Elimina espacios en blanco al inicio y al final
    $sedeId = trim($_POST['sedeId']); // (modificado) Elimina espacios en blanco al inicio y al final

    // Validar que los campos no estén vacíos
    if (empty($nombreEspacio) || empty($capacidad) || empty($sedeId)) {
        // Redirigir con mensaje de error (modificado: ahora con alerta)
        echo "<script>
                alert('Por favor, completa todos los campos');
                window.location.href = '../Visualizaciones/crear_espacio.php';
              </script>";
        exit; // (modificado) Termina la ejecución del script
    }

    // Preparar los datos para la inserción
    $data = [
        'name_space' => $nombreEspacio, // Nombre del espacio
        'capacity' => $capacidad, // Capacidad del espacio
        'id_location' => $sedeId, // ID de la sede
        'state_space' => true // Estado del espacio (activo)
    ];

    // Intentar insertar los datos en la base de datos
    if (insert($conexion, 'spaces', $data)) { // (modificado) Llama a la función insert
        // Redirigir con mensaje de éxito (modificado: ahora con alerta)
        echo "<script>
                alert('Espacio creado exitosamente');
                window.location.href = '../Visualizaciones/espacios.php';
              </script>";
    } else {
        // Redirigir con mensaje de error (modificado: ahora con alerta)
        echo "<script>
                alert('Error al crear el espacio');
                window.location.href = '../Visualizaciones/crear_espacio.php';
              </script>";
    }
    exit; // (modificado) Termina la ejecución del script
}
?>