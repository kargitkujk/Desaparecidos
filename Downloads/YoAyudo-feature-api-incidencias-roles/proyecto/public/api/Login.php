<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/Login.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

$datos = json_decode(file_get_contents("php://input"), true);
$cedula     = trim($datos["cedula"] ?? "");
$contrasena = trim($datos["contrasena"] ?? "");

if ($cedula === "" || $contrasena === "") {
    RespuestaJson::error("Cédula y contraseña son obligatorias", 422);
}

$login = new Login();
$resultado = $login->autenticar($cedula, $contrasena);

if (!$resultado["ok"]) {
    switch ($resultado["error"]) {
        case "credenciales":
            RespuestaJson::error("Credenciales inválidas", 401);
            break;
        case "inactivo":
            RespuestaJson::error("Usuario inactivo", 403);
            break;
        case "sin_roles":
            RespuestaJson::error("Usuario sin roles asignados", 403);
            break;
        default:
            RespuestaJson::error("Error de autenticación", 400);
    }
} else {
    $_SESSION["usuario_id"] = $resultado["usuario"]["id"];
    RespuestaJson::exito($resultado["usuario"], "Login exitoso", 200);
}
