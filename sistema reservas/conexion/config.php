<?php
// Conexión a la base de datos
$host = 'localhost:3306'; // Especifica el host y el puerto de la base de datos
$user = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña del usuario de la base de datos
$db = 'reservas_biblioteca'; // Nombre de la base de datos a la que se quiere conectar

// Crear una nueva conexión a la base de datos utilizando mysqli
$conexion = new mysqli($host, $user, $password, $db);

// Verificar si hubo un error en la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error); // Termina el script y muestra el error
}

// Obtener la fecha actual en formato Y-m-d
$fechaActual = date("Y-m-d");

// Actualizar todas las reservas anteriores a hoy que estén activas (state_reservation = 1)
$sql = "UPDATE reservations 
        SET state_reservation = 0 
        WHERE date_reserv < ? AND state_reservation = 1";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $fechaActual); // Corregido: "s" porque es string

// Obtener programas
$programas = $conexion->query("SELECT id_programa, nombre_programa FROM programas ORDER BY nombre_programa ASC");


if ($stmt->execute()) {

} else {
    echo "Error al actualizar reservas: " . $stmt->error;
}

$stmt->close();
// NOTA: Si necesitas seguir usando $conexion en otro archivo, NO.
// 
//  lo cierres aquí
// $conexion->close(); <-- comenta o elimina esto si vas a usar la conexión luego
?>
