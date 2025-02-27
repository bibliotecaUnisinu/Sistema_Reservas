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