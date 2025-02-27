<?php
// funciones/verificar_disponibilidad.php

function verificarDisponibilidad($conexion, $id_space, $fecha_reserv, $hora_inicio, $horas_reserv, $minutos_reserv) {
    // Calcular la hora de finalización de la reserva
    $hora_fin = date("H:i:s", strtotime("+$horas_reserv hours +$minutos_reserv minutes", strtotime($hora_inicio)));

    // Consulta para verificar si hay reservas en el mismo espacio y fecha
    $sql = "SELECT * FROM reservations 
            WHERE id_space = ? 
            AND date_reserv = ? 
            AND (
                (start_time < ? AND DATE_ADD(start_time, INTERVAL hours_reserv HOUR) > ?) OR
                (start_time < ? AND DATE_ADD(start_time, INTERVAL hours_reserv HOUR) > ?)
            )";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_space, $fecha_reserv, $hora_inicio, $hora_fin, $hora_fin, $hora_inicio]);

    return $stmt->rowCount() === 0; // Devuelve true si está disponible
}
?>