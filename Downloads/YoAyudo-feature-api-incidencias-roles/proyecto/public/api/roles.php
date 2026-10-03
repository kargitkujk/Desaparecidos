<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/RolController.php";
require_once RUTA_VISTA . "/RespuestaJson.php";



$controlador = new RolController();
$metodo = $_SERVER["REQUEST_METHOD"];
$controlador->gestionar($metodo);

if ($metodo === "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];
    if (!is_scalar($id)) {
        RespuestaJson::error("Id de rol inválido", 422);
        return;
    }

    $id = trim((string) $id);
    if (!preg_match('/^\d+$/', $id)) {
        RespuestaJson::error("Id de rol inválido", 422);
        return;
    }
}