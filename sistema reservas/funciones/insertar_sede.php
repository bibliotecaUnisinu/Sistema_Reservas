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
        // Redirigir con mensaje de error (modificado: ahora con alerta)
        echo "<script>
                alert('Por favor, completa todos los campos');
                window.location.href = '../Visualizaciones/crear_sede.php';
              </script>";
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
        // Redirigir con mensaje de éxito (modificado: ahora con alerta)
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Sede creada exitosamente!',
                            text: 'Serás redirigido en unos segundos...',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.href = '../Visualizaciones/sedes.php';
                            }
                        });
                    };
                </script>";


    } else {
        // Redirigir con mensaje de error (modificado: ahora con alerta)
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al crear la sede',
                        text: 'Ocurrió un problema al guardar los datos. Intenta nuevamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../Visualizaciones/crear_sede.php';
                    });
                };
            </script>";
    }
    exit; // (modificado) Termina la ejecución del script
}
?>