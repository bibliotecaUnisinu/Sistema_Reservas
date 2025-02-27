<?php
// Iniciar la sesión
session_start(); // Inicia o reanuda la sesión actual

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    // Si no hay sesión de administrador activa, redirigir al login
    header("Location: ../Visualizaciones/login.php"); // Redirige al usuario a la página de inicio de sesión
    exit(); // Termina el script para evitar que se ejecute el resto del código
}

include('../funciones/insertar_sede.php'); // Incluye el archivo que maneja la lógica para insertar sedes
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title>Crear Sedes | Biblioteca</title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluye scripts necesarios -->
    <link rel="stylesheet" href="../styles/styles_crear_sede_espacio.css"> <!-- Incluye la hoja de estilos para crear sedes -->
</head>

<body>
    <?php include('../includes/header.php'); ?> <!-- Incluye el encabezado de la página -->

    <main class="centered-main"> <!-- Contenedor principal centrado -->
        <div class="location-container"> <!-- Contenedor para el formulario -->
            <form id="crearSedeForm" action="../funciones/insertar_sede.php" method="POST" onsubmit="return validarFormulario();">
                <img src="../Imagenes/favicon.ico" alt="Logo"> <!-- Logo de la biblioteca -->

                <!-- Banner de error que aparecerá si hay campos vacíos o credenciales incorrectas -->
                <div id="errorBanner" style="display: none;" class="error-banner">
                    <p>Por favor, complete todos los campos.</p> <!-- Mensaje de error -->
                </div>

                <h1>Crear sede</h1> <!-- Título del formulario -->

                <div class="input-group" id="nombreSede-group"> <!-- Grupo de entrada para el nombre de la sede -->
                    <label for="nombreSede">Nombre </label>
                    <input type="text" id="nombreSede" name="nombreSede" placeholder="Nombre de la sede"> <!-- Campo de entrada para el nombre -->
                </div>

                <div class="input-group" id="direccion-group"> <!-- Grupo de entrada para la dirección -->
                    <label for="direccion">Dirección </label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección de la sede"> <!-- Campo de entrada para la dirección -->
                </div>

                <div class="input-group" id="contacto-group"> <!-- Grupo de entrada para el contacto -->
                    <label for="contacto">Contacto </label>
                    <input type="text" id="contacto" name="contacto" placeholder="Contacto de la sede"> <!-- Campo de entrada para el contacto -->
                </div>
                
                <input type="hidden" name="state_location" value="1"> <!-- Campo oculto para el estado de la sede -->

                <button type="submit">Crear</button> <!-- Botón para enviar el formulario -->
            </form>
        </div>
        
    </main>

    <script src="../scripts/crear_sede.js"></script> <!-- Incluye el script para la validación del formulario -->
</body>

</html>
