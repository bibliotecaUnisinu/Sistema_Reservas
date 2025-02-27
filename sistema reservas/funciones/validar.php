<?php

// Recoger datos del formulario
$usuario = $_POST['usuario']; // (modificado) Recoger el usuario
$contrasena = $_POST['contrasena']; // (modificado) Recoger la contraseña
session_start(); // Iniciar sesión

include('../conexion/config.php'); // Incluir el archivo de configuración de la base de datos

// Consulta para obtener los datos del administrador
$consulta = "SELECT * FROM admins WHERE dni_admin=? AND pass_admin=?"; // (modificado) Usar consultas preparadas para evitar inyecciones SQL
$stmt = $conexion->prepare($consulta); // (modificado) Preparar la consulta
$stmt->bind_param("ss", $usuario, $contrasena); // (modificado) Vincular parámetros
$stmt->execute(); // (modificado) Ejecutar la consulta
$resultado = $stmt->get_result(); // (modificado) Obtener el resultado

// Verificar si el administrador existe
if ($resultado->num_rows > 0) { // (modificado) Verificar si hay resultados
    $admin = $resultado->fetch_assoc(); // (modificado) Obtener el administrador como un array asociativo
    // Guardar información relevante en la sesión
    $_SESSION['admin_id'] = $admin['id_admin']; // (modificado) Guardar ID del administrador
    $_SESSION['admin_name'] = $admin['name_admin']; // (modificado) Guardar nombre del administrador
    $_SESSION['admin_surname'] = $admin['surname_admin']; // (modificado) Guardar apellido del administrador
    $_SESSION['admin_email'] = $admin['email_admin']; // (modificado) Guardar correo del administrador
    $_SESSION['admin_user_type'] = $admin['user_type']; // (modificado) Guardar tipo de usuario del administrador

    // Redirigir al panel del administrador
    header("Location: ../Visualizaciones/administrador.php"); // (modificado) Redirigir al panel
    exit(); // (modificado) Terminar la ejecución
} else {
    // Si las credenciales no son válidas, redirigir con un parámetro de error
    header("Location: ../Visualizaciones/login.php?error=invalid_credentials"); // (modificado) Redirigir con mensaje de error
    exit(); // (modificado) Terminar la ejecución
}

$stmt->close(); // (modificado) Cerrar la declaración
mysqli_close($conexion); // (modificado) Cerrar la conexión
?>