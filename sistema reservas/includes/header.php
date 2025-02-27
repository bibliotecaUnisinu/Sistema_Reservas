<header>
    <div class="imagen-izquierda">
        <img src="../Imagenes/logo-unisinu-cartagena.ico" alt="Logo Unisinu">
    </div>

    <!-- Barra de navegación si estamos en la vista de administrador -->
    <?php if (in_array(basename($_SERVER['PHP_SELF']), ['administrador.php', 'crear_espacio.php', 'crear_sede.php', 'sedes.php', 'espacios.php', 'reportes.php'])): ?>
        <!-- Menú de navegación -->
        <div class="menu__bar">
            <ul>
                <li><a href="../Visualizaciones/administrador.php">Reservas</a></li>
                <li><a href="../Visualizaciones/sedes.php">Sedes</a></li>
                <li><a href="../Visualizaciones/espacios.php">Espacios</a></li>
                <li><a href="../Visualizaciones/reportes.php">Reportes</a></li>
            </ul>
        </div>
        <div class="imagen-derecha">
            <img src="../Imagenes/opciones.ico" alt="Opciones" id="toggle-menu">
        </div>
    <?php else: ?>
        <!-- Vista para cualquier otra página, en lugar del menú lateral mostramos el ícono de login -->
        <div class="imagen-derecha">
            <a href="../Visualizaciones/login.php">
                <img src="../Imagenes/opciones.ico" alt="Login">
            </a>
        </div>
    <?php endif; ?>
</header>
<!-- Menú lateral oculto -->
<div class="sidebar" id="sidebar">
    <span class="close-btn" id="close-menu">
        <i class='bx bx-x'></i>
    </span>
    <div class="admin-info">
        <div class="admin-avatar">
            <i class='bx bxs-user-circle'></i>
        </div>
        <div class="admin-details">
            <p><i class='bx bxs-user'></i> <strong><?php echo $_SESSION['admin_name'] . " " . $_SESSION['admin_surname']; ?></strong></p>
            <p><i class='bx bxs-envelope'></i> <?php echo $_SESSION['admin_email']; ?></p>
            <p><i class='bx bxs-badge-check'></i> <em><?php echo $_SESSION['admin_user_type']; ?></em></p>
        </div>
    </div>

    <ul>
        <li><a href="#"><i class='bx bxs-lock-alt'></i> Cambiar contraseña</a></li>
        <li><a href="../funciones/logout.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a></li>
    </ul>
</div>

<!-- Script para controlar el calendario y otros elementos -->
<script src="../scripts/menuLateral.js"></script>