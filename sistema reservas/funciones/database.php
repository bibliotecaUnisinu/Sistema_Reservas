<?php
require_once ('../conexion/config.php'); // Incluye el archivo de configuración de la base de datos

// Función para insertar datos en la tabla
function insert($conn, $table, $data) {
    $columns = implode(", ", array_keys($data)); // Obtener las columnas
    $placeholders = implode(", ", array_fill(0, count($data), '?')); // Usar ? como placeholder
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)"; // Consulta SQL
    $stmt = $conn->prepare($sql); // Preparar la consulta
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error); // (modificado) Manejo de errores más claro
    }

    // Determinamos los tipos de datos para bind_param ('s' para strings, 'i' para enteros, etc.)
    $types = str_repeat('s', count($data)); // (modificado) Considera ajustar esto si hay tipos de datos diferentes

    // Convertimos el array de datos en valores para bind_param
    $values = array_values($data);
    
    // bind_param espera referencias, así que usamos el operador `...` para pasar los valores
    $stmt->bind_param($types, ...$values);
    
    return $stmt->execute(); // Ejecutar la consulta
}

// Función para seleccionar datos
function select($conn, $table, $where = "") {
    $sql = "SELECT * FROM $table " . ($where ? "WHERE $where" : ""); // Consulta SQL
    $stmt = $conn->prepare($sql); // Preparar la consulta
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error); // (modificado) Manejo de errores más claro
    }

    $stmt->execute(); // Ejecutar la consulta
    $result = $stmt->get_result(); // Obtener el resultado
    return $result->fetch_all(MYSQLI_ASSOC); // (modificado) Retornar todos los resultados como un array asociativo
}

// Función para actualizar datos
function update($conn, $table, $data, $where) {
    $set = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data))); // Crear la parte SET de la consulta

    // Manejo de condición WHERE
    $whereCondition = is_array($where)
        ? implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where))) // (modificado) Manejo de condiciones WHERE
        : $where;

    $sql = "UPDATE $table SET $set WHERE $whereCondition"; // Consulta SQL
    $stmt = $conn->prepare($sql); // Preparar la consulta
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error); // (modificado) Manejo de errores más claro
    }

    $params = array_merge(array_values($data), is_array($where) ? array_values($where) : []); // Combinar parámetros
    $types = str_repeat('s', count($params)); // (modificado) Considera ajustar esto si hay tipos de datos diferentes
    
    $stmt->bind_param($types, ...$params); // Vincular parámetros

    return $stmt->execute(); // Ejecutar la consulta
}

// Función para eliminar datos
function delete($conn, $table, $where) {
    $sql = "DELETE FROM $table WHERE $where"; // Consulta SQL
    $stmt = $conn->prepare($sql); // Preparar la consulta
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error); // (modificado) Manejo de errores más claro
    }

    return $stmt->execute(); // Ejecutar la consulta
}
?>