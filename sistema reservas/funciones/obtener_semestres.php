<?php
// obtener_semestres.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../conexion/config.php'; // Asegúrate de que esta ruta sea correcta

// Verificar si se envió el parámetro por GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $idPrograma = $_GET['id_programa'] ?? null;

    if (!$idPrograma) {
        echo json_encode(['error' => 'ID de programa no proporcionado']);
        exit;
    }

    // Prepara la consulta para obtener la cantidad de semestres del programa
    $stmt = $conexion->prepare("SELECT semestre FROM programas WHERE id_programa = ?");
    $stmt->bind_param("i", $idPrograma);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if ($resultado && isset($resultado['semestre'])) {
        $cantidadSemestres = intval($resultado['semestre']);

        // Generar array de semestres desde 1 hasta el total
        $semestres = [];
        for ($i = 1; $i <= $cantidadSemestres; $i++) {
            $semestres[] = $i;
        }

        echo json_encode($semestres);
    } else {
        echo json_encode(['error' => 'Programa no encontrado o sin semestres']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>
