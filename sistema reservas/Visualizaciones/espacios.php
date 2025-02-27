<?php
require_once('../funciones/database.php'); // Incluye el archivo de funciones de base de datos
session_start(); // Inicia o reanuda la sesión actual

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    header("Location: ../Visualizaciones/login.php"); // Redirige al usuario a la página de inicio de sesión si no está autenticado
    exit(); // Termina el script para evitar que se ejecute el resto del código
}

// Obtener todos los espacios habilitados
$resultado = select($conexion, 'spaces WHERE state_space = 1'); // Llama a la función select para obtener los espacios habilitados
$sedes = select($conexion, 'locations WHERE state_location = 1'); // Llama a la función select para obtener las sedes activas

// Crear un mapa de sedes habilitadas
$sedesMap = []; // Inicializa un array vacío para almacenar las sedes
foreach ($sedes as $sede) {
    $sedesMap[$sede['id_location']] = $sede['name_location']; // Asocia el ID de la sede con su nombre
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title> Espacios | Biblioteca </title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluye scripts necesarios -->
    <link rel="stylesheet" href="../styles/styles_sedes_espacios.css"> <!-- Incluye la hoja de estilos para sedes y espacios -->
</head>

<body>
    <?php include('../includes/header.php'); ?> <!-- Incluye el encabezado de la página -->

    <h1>Espacios</h1> <!-- Título de la sección de espacios -->

    <!-- Contenedor para las tarjetas de espacios -->
    <div class="sedes-container">
        <?php foreach ($resultado as $row): ?> <!-- Itera sobre los resultados de los espacios -->
            <?php
            // Solo mostrar los espacios si la sede está habilitada
            if (isset($sedesMap[$row['id_location']])) { // Verifica si la sede está habilitada
                $nombreCompletoSede = htmlspecialchars($sedesMap[$row['id_location']]); // Escapa el nombre de la sede
                $nombresSede = explode(' ', $nombreCompletoSede); // Divide el nombre de la sede en palabras
                $primerNombreSede = $nombresSede[0]; // Obtiene el primer nombre de la sede

                $nombreCompletoEspacio = htmlspecialchars($row['name_space']); // Escapa el nombre del espacio
            ?>
                <div class="sede-card"
                    data-sede-id="<?php echo $row['id_location']; ?>" data-id="<?php echo $row['id_space']; ?>"
                    onclick="openModal('<?php echo $nombreCompletoEspacio; ?>', '<?php echo htmlspecialchars($row['capacity']); ?>', '<?php echo $nombreCompletoSede; ?>', '<?php echo $row['id_space']; ?>', '<?php echo $row['state_space']; ?>')">
                    <h2><?php echo $primerNombreSede; ?></h2> <!-- Muestra el primer nombre de la sede -->
                    <p class="espacio-name"><?php echo $nombreCompletoEspacio; ?></p> <!-- Muestra el nombre del espacio -->
                </div>
            <?php } ?>
        <?php endforeach; ?>

        <!-- Botón para crear nuevo espacio -->
        <div class="sede-card boton-crear-espacio" onclick="location.href='crear_espacio.php'">
            <span class="icono-mas">+</span> <!-- Icono para crear un nuevo espacio -->
        </div>
    </div>

    <!-- Modal para mostrar la información del espacio -->
    <div id="espacioModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span> <!-- Botón para cerrar el modal -->
            <h2 id="modal-name"></h2> <!-- Nombre del espacio en el modal -->
            <p><b>Capacidad: </b><span id="modal-capacity"></span></p> <!-- Capacidad del espacio -->
            <p><b>Sede: </b><span id="modal-location"></span></p> <!-- Sede del espacio -->
            <button onclick="openEditModal()">Editar</button> <!-- Botón para abrir el modal de edición -->
        </div>
    </div>

    <!-- Modal de edición del espacio -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span> <!-- Botón para cerrar el modal de edición -->
            <h2>Editar Espacio</h2> <!-- Título del modal de edición -->
            <form id="editForm" action="../funciones/actualizar_espacio.php" method="POST"> <!-- Formulario para editar el espacio -->
            <input type="hidden" id="edit-id-space" name="id_space"> <!-- Campo oculto para el ID del espacio -->

<div class="form-group">
    <label for="edit-state-space">¿Habilitar espacio?</label>
    <input type="checkbox" id="edit-state-space" name="state_space"> <!-- Checkbox para habilitar o deshabilitar el espacio -->
</div>

<div for="edit-location">Sede:</div>
<input type="text" id="edit-location" disabled> <!-- Campo de texto para mostrar la sede, deshabilitado para edición -->

<div for="edit-name-space">Nombre del Espacio:</div>
<input type="text" id="edit-name-space" name="name_space" required> <!-- Campo de entrada para el nombre del espacio, requerido -->

<div for="edit-capacity">Capacidad:</div>
<div class="input-container">
    <span class="input-suffix">Personas</span> <!-- Sufijo que indica la unidad de medida -->
    <input type="number" id="edit-capacity" name="capacity" required> <!-- Campo de entrada para la capacidad, requerido -->
</div>

<button type="submit">Guardar cambios</button> <!-- Botón para enviar el formulario de edición -->
</form>
</div>
</div>

<!-- Modal de Confirmación -->
<div id="modalConfirmacion" class="modal">
<div class="modal-content">
<p>¿Está seguro de que desea editar el espacio?</p> <!-- Mensaje de confirmación -->
<button id="btnAceptar">Aceptar</button> <!-- Botón para confirmar la edición -->
<button id="btnCancelar">Cancelar</button> <!-- Botón para cancelar la edición -->
</div>
</div>

<script src="../scripts/espacios.js"></script> <!-- Incluye el script para manejar la lógica de los espacios -->
</body>

</html>