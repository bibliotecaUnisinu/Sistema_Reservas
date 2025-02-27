<?php
// Cerrar sesión
session_start(); // (modificado) Inicia la sesión para poder destruirla
session_destroy(); // (modificado) Destruye la sesión actual

header("Location: ../Visualizaciones/inicio.php"); // Redirige a la página de inicio
exit(); // (modificado) Termina la ejecución del script
?>