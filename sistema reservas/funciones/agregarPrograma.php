<?php
require_once('../conexion/config.php');

if (isset($_POST['nombre_programa']) && trim($_POST['nombre_programa']) !== '' && 
    isset($_POST['semestre']) && is_numeric($_POST['semestre']) && 
    $_POST['semestre'] >= 1 && $_POST['semestre'] <= 12) {
    
    $nombrePrograma = trim($_POST['nombre_programa']);
    $semestres = (int)$_POST['semestre'];
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        echo 'error_conexion: ' . $conexion->connect_error;
        exit;
    }
    
    // Preparar la inserción
    $stmt = $conexion->prepare("INSERT INTO programas (nombre_programa, semestre) VALUES (?, ?)");
    if (!$stmt) {
        echo 'error_prepare: ' . $conexion->error;
        exit;
    }
    
    // Ligamos los parámetros y ejecutamos
    $stmt->bind_param("si", $nombrePrograma, $semestres);
    if ($stmt->execute()) {
        // Devolvemos el ID recién insertado
        echo $stmt->insert_id;
    } else {
        echo 'error_execute: ' . $stmt->error;
    }
    $stmt->close();
} else {
    echo 'error_post: datos inválidos';
}
?>