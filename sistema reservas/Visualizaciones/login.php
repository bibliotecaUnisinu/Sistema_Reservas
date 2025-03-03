<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <link rel="stylesheet" href="../styles/styles_login.css"> <!-- Incluye la hoja de estilos para la página de inicio de sesión -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css"> <!-- Incluye los iconos de Boxicons -->
    <title>Iniciar Sesión | Biblioteca</title> <!-- Título de la página -->
    <link rel="icon" href="../Imagenes/favicon.ico" type="image/x-icon"> <!-- Icono de la pestaña -->
</head>

<body>
    <div class="login-container"> <!-- Contenedor principal para el formulario de inicio de sesión -->
        <form action="../funciones/validar.php" method="POST"> <!-- Formulario que envía datos a validar.php -->
            <img src="../Imagenes/favicon.ico" alt="Logo"> <!-- Logo de la biblioteca -->

            <!-- Banner de error que aparecerá si hay campos vacíos o credenciales incorrectas -->
            <div id="errorBanner" style="display: none;" class="error-banner">
                <p>Acceso inválido. Por favor, complete todos los campos.</p> <!-- Mensaje de error para campos vacíos -->
            </div>
            <div id="loginErrorBanner" class="error-banner" style="display: none;">
                <p>El ID administrador o la contraseña son incorrectos.</p> <!-- Mensaje de error para credenciales incorrectas -->
            </div>
            <div class="input-group" id="usuario-group"> <!-- Grupo de entrada para el usuario -->
                <i class='bx bxs-user'></i> <!-- Icono de usuario -->
                <label for="usuario">Usuario:</label> <!-- Etiqueta para el campo de usuario -->
                <input type="text" id="usuario" name="usuario" placeholder="ID administrador"> <!-- Campo de entrada para el ID del administrador -->
            </div>

            <div class="input-group" id="contrasena-group"> <!-- Grupo de entrada para la contraseña -->
                <i class='bx bxs-lock-alt'></i> <!-- Icono de candado -->
                <label for="contrasena">Contraseña:</label> <!-- Etiqueta para el campo de contraseña -->
                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña"> <!-- Campo de entrada para la contraseña -->
                <i class='bx bx-show' id="togglePassword" style="cursor: pointer;"></i> <!-- Icono para mostrar/ocultar la contraseña -->
            </div>

            <button type="submit">Acceder</button> <!-- Botón para enviar el formulario -->
        </form>
        <a href="#">¿Olvidó su contraseña?</a> <!-- Enlace para recuperar la contraseña -->
    </div>

    <script src="../scripts/scripts_login.js"></script> <!-- Incluye el script para manejar la lógica de inicio de sesión -->
</body>

</html>