<?php

require_once "../modelo/IncidenciaDAO.php";

/**
 * Devuelve estadísticas de las incidencias registradas en formato JSON.
 * Delega la consulta a IncidenciaDAO; este controlador no
 * contiene SQL.
 */

header('Content-Type: application/json');

try {
    $incidenciaDAO = new IncidenciaDAO();
    $estadisticas = $incidenciaDAO->obtenerEstadisticas();

    echo json_encode($estadisticas);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error del servidor al obtener las estadísticas."
    ]);
}
