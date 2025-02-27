<?php
// Iniciar la sesión
session_start(); // Inicia o reanuda la sesión actual

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    // Si no hay sesión de administrador activa, redirigir al login
    header("Location: ../Visualizaciones/login.php"); // Redirige al usuario a la página de inicio de sesión
    exit(); // Termina el script para evitar que se ejecute el resto del código
}

require_once '../funciones/database.php'; // Incluye el archivo de funciones de base de datos

// Obtener las sedes activas
$sedes = select($conexion, 'locations WHERE state_location = 1'); // Llama a la función select para obtener las sedes activas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title>Crear Espacio | Biblioteca</title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluye scripts necesarios -->
    <link rel="stylesheet" href="../styles/styles_crear_sede_espacio.css"> <!-- Incluye la hoja de estilos para crear espacio -->
</head>

<body>
    <?php include('../includes/header.php'); ?> <!-- Incluye el encabezado de la página -->

    <main class="centered-main"> <!-- Contenedor principal centrado -->
        <div class="location-container"> <!-- Contenedor para el formulario -->
            <!-- Formulario con validación para "Crear Espacio" -->
            <form id="crearEspacioForm" action="../funciones/insertar_espacio.php" method="POST" onsubmit="return validarFormulario();">
                <img src="../Imagenes/favicon.ico" alt="Logo Biblioteca"> <!-- Logo de la biblioteca -->

                <!-- Banner de error que aparecerá si hay campos vacíos -->
                <div id="errorBanner" style="display: none;" class="error-banner">
                    <p>Por favor, complete todos los campos.</p> <!-- Mensaje de error -->
                </div>

                <h1>Crear Espacio</h1> <!-- Título del formulario -->

                <div class="input-select" id="sedeId-group"> <!-- Selector de sede -->
                    <label for="sedeId">Seleccionar Sede:</label>
                    <select id="sedeId" name="sedeId"> <!-- Selector de sedes -->
                        <option value="">Seleccione una sede</option> <!-- Opción por defecto -->
                        <?php foreach ($sedes as $sede): ?> <!-- Itera sobre las sedes obtenidas -->
                            <option value="<?php echo htmlspecialchars($sede['id_location']); ?>">
                                <?php echo htmlspecialchars($sede['name_location']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group" id="nombreEspacio-group"> <!-- Grupo de entrada para el nombre del espacio -->
                    <label for="nombreEspacio">Nombre del Espacio:</label>
                    <input type="text" id="nombreEspacio" name="nombreEspacio" placeholder="Nombre del espacio"> <!-- Campo de entrada para el nombre -->
                </div>

                <div class="input-group" id="capacidad-group"> <!-- Grupo de entrada para la capacidad -->
                    <label for="capacidad">Capacidad:</label>
                    <input type="number" id="capacidad" name="capacidad" placeholder="Capacidad del espacio"> <!-- Campo de entrada para la capacidad -->
                </div>

                <button type="submit">Crear</button> <!-- Botón para enviar el formulario -->
            </form>
        </div>
    </main>

    <script src="../scripts/crear_espacio.js"></script> <!-- Incluye el script para la validación del formulario -->
</body>

</html>