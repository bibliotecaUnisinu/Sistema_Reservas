<?php
require_once('../funciones/database.php'); // Incluir el archivo de funciones de base de datos
session_start(); // Iniciar la sesión

// Verificar si la sesión del administrador está activa
if (!isset($_SESSION['admin_name'])) {
    header("Location: ../Visualizaciones/login.php"); // Redirigir al login si no hay sesión activa
    exit(); // Terminar el script
}

// Consulta para obtener solo las sedes habilitadas
$resultado = select($conexion, 'locations WHERE state_location = 1'); // Obtener sedes habilitadas
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurar la vista para dispositivos móviles -->
    <title>Sedes | Bibliotecas</title> <!-- Título de la página -->
    <?php include('../scripts/scripts.php'); ?> <!-- Incluir scripts necesarios -->
    <link rel="stylesheet" href="../styles/styles_sedes_espacios.css"> <!-- Hoja de estilos para sedes y espacios -->
</head>

<body>

    <?php include('../includes/header.php'); ?> <!-- Incluir el encabezado de la página -->

    <h1>Sedes</h1> <!-- Título de la sección de sedes -->

    <!-- Contenedor para las tarjetas de sedes -->
    <div class="sedes-container">
        <?php foreach ($resultado as $row): ?> <!-- Iterar sobre las sedes obtenidas -->
            <?php
            $nombreCompleto = htmlspecialchars($row['name_location']); // Escapar el nombre de la sede
            $nombres = explode(' ', $nombreCompleto); // Dividir el nombre en palabras
            $primerNombre = $nombres[0]; // Obtener el primer nombre
            ?>
            <div class="sede-card"
                data-id="<?php echo $row['id_location']; ?>" 
                onclick="openModal('<?php echo $nombreCompleto; ?>', '<?php echo htmlspecialchars($row['addres']); ?>', '<?php echo htmlspecialchars($row['contact']); ?>', '<?php echo $row['id_location']; ?>', '<?php echo $row['state_location']; ?>')">
                <h2><?php echo $primerNombre; ?></h2> <!-- Mostrar el primer nombre de la sede -->
                <p class="estado">Estado: Habilitada</p> <!-- Mostrar el estado de la sede -->
            </div>
        <?php endforeach; ?>

        <!-- Botón para crear nueva sede -->
        <div class="sede-card boton-crear-sede" onclick="location.href='crear_sede.php'">
            <span class="icono-mas">+</span> <!-- Icono para crear nueva sede -->
        </div>
    </div>

    <!-- Modal para mostrar la información de la sede -->
    <div id="sedeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span> <!-- Botón para cerrar el modal -->
            <h2 id="modal-name"></h2> <!-- Nombre de la sede en el modal -->
            <p><b>Dirección: </b><span id="modal-address"></span></p> <!-- Dirección de la sede -->
            <p><b>Contacto: </b><span id="modal-contact"></span></p> <!-- Contacto de la sede -->
            <button onclick="openEditModal()">Editar</button> <!-- Botón para abrir el modal de edición -->
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span> <!-- Botón para cerrar el modal de edición -->
            <h2>Editar sede</h2> <!-- Título del modal de edición -->
            <form id="editForm" action="../funciones/actualizar_sede.php" method="POST"> <!-- Formulario para editar la sede -->
                <input type="hidden" id="edit-id" name="id"> <!-- Campo oculto para el ID de la sede -->

                <div class="form-group">
                    <label for="edit-state" class="checkbox-label">¿Habilitar sede?</label>
                    <input type="checkbox" id="edit-state" name="state_location" onchange="toggleSedeVisibility(selectedSede.id)"> <!-- Checkbox para habilitar la sede -->
                </div>

                <div for="nombreSede">Nombre de la sede:</div>
                <input type="text" id="edit-nombre" name="nombreSede" required><br> <!-- Campo para el nombre de la sede -->

                <div for="direccion">Dirección:</div>
                <input type="text" id="edit-direccion" name="direccion" required><br> <!-- Campo para la dirección -->

                <div for="contacto">Contacto:</div>
                <input type="text" id="edit-contacto" name="contacto" required><br> <!-- Campo para el contacto -->

                <button type="submit">Guardar cambios</button> <!-- Botón para guardar los cambios -->
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="modalConfirmacion" class="modal">
        <div class="modal-content">
            <p>¿Está seguro de que desea editar la sede?</p> <!-- Mensaje de confirmación -->
            <button id="btnAceptar">Aceptar</button> <!-- Botón para aceptar -->
            <button id="btnCancelar">Cancelar</button> <!-- Botón para cancelar -->
        </div>
    </div>

    <script src="../scripts/sedes.js"></script> <!-- Incluir el script para manejar la lógica de sedes -->

</body>

</html>