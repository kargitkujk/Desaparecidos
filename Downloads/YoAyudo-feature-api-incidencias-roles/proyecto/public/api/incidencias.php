<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/config.php";

$baseDir = dirname(__DIR__, 2);
$modelPaths = [
    RUTA_MODELO . "/AccesoDatosUsuario.php",
    $baseDir . "/modelos/AccesoDatosUsuario.php",
    $baseDir . "/app/modelos/AccesoDatosUsuario.php",
    $baseDir . "/src/modelos/AccesoDatosUsuario.php",
];
foreach ($modelPaths as $path) {
    if (!empty($path) && file_exists($path)) {
        require_once $path;
        break;
    }
}

$vistaPaths = [
    RUTA_VISTA . "/RespuestaJson.php",
    $baseDir . "/vistas/RespuestaJson.php",
    $baseDir . "/app/vistas/RespuestaJson.php",
    $baseDir . "/src/vistas/RespuestaJson.php",
];
foreach ($vistaPaths as $path) {
    if (!empty($path) && file_exists($path)) {
        require_once $path;
        break;
    }
}

$datos = json_decode(file_get_contents("php://input"), true);
$usuario = $datos["usuario"] ?? "";
$clave   = $datos["clave"] ?? "";

$daoClass = null;
$daoClasses = [
    "AccesoDatosUsuario",
    "\\AccesoDatosUsuario",
    "App\\Modelos\\AccesoDatosUsuario",
    "app\\modelos\\AccesoDatosUsuario",
    "App\\AccesoDatosUsuario",
    "app\\AccesoDatosUsuario",
    "modelos\\AccesoDatosUsuario",
    "src\\modelos\\AccesoDatosUsuario",
];

foreach ($daoClasses as $candidate) {
    if (class_exists($candidate)) {
        $daoClass = $candidate;
        break;
    }
}

if ($daoClass === null) {
    RespuestaJson::error("No se pudo inicializar el acceso a datos del usuario", 500);
    exit;
}

$dao = new $daoClass();
$usuarioId = null;

if (method_exists($dao, "validarCredenciales")) {
    $usuarioId = $dao->validarCredenciales($usuario, $clave);
} elseif (method_exists($dao, "login")) {
    $usuarioId = $dao->login($usuario, $clave);
}

if ($usuarioId) {
    $_SESSION["usuario_id"] = $usuarioId;
    RespuestaJson::exito(null, "Login exitoso", 200);
} else {
    RespuestaJson::error("Usuario o contraseña incorrectos", 401);
}
