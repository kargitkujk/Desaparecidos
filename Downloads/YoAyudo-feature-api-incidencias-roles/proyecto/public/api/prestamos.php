<?php
require_once __DIR__ . '/../../config/config.php';
require_once RUTA_CONTROLADOR . '/PrestamoController.php';
require_once RUTA_MODELO . '/ConectorPDO.php';
require_once RUTA_VISTA . '/RespuestaJson.php';
header('Content-Type: application/json');

$controller = new PrestamoController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $controller->crearPrestamo();
        break;

    case 'GET':
        if (isset($_GET['id_usuario'])) {
            $controller->obtenerHistorialPorUsuario((int) $_GET['id_usuario']);
        } else {
            RespuestaJson::error(400, "Falta parámetro id_usuario");
        }
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $controller->cerrarPrestamo((int) $_GET['id']);
        } else {
            RespuestaJson::error(400, "Falta parámetro id");
        }
        break;

    default:
        header("Allow: POST, GET, PUT");
        RespuestaJson::error(405, "Método no permitido");
        break;
}
