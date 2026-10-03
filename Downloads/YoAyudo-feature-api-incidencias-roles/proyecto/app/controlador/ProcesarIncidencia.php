<?php
session_start();

require_once dirname(__DIR__) . "/modelo/IncidenciaDAO.php";
require_once dirname(__DIR__) . "/modelo/UsuarioDAO.php";

/**
 * Procesa el formulario de registro de una incidencia.
 * Obtiene los datos enviados mediante POST, realiza las validaciones
 * correspondientes y, si son correctos, delega el registro en la base
 * de datos a IncidenciaDAO. Este controlador solo recibe, valida y redirige,
 * no accede a la base de datos ni genera salida en pantalla directamente,
 * eso le corresponde a la vista.
 */

$usuario_id = $_SESSION["usuario_id"] ?? null;

if (!$usuario_id) {
    header("Location: ../vistas/Login.php");
    exit;
}

$usuarioDAO = new UsuarioDAO();

try {
    $empleado_id = $usuarioDAO->obtenerEmpleadoIdPorUsuario($usuario_id);
} catch (PDOException $e) {
    header("Location: ../vistas/incidencia.php?error=" . urlencode(
        "Error del servidor. Intentá nuevamente más tarde."
    ));
    exit;
}

if (!$empleado_id) {
    header("Location: ../vistas/incidencia.php?error=" . urlencode(
        "No hay un empleado asociado a este usuario"
    ));
    exit;
}

function limpiar($dato)
{
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../vistas/incidencia.php");
    exit;
}

$errores = [];

$laboratorio = limpiar($_POST["laboratorio"] ?? "");
$tipo        = limpiar($_POST["tipo"] ?? "");
$descripcion = limpiar($_POST["descripcion"] ?? "");

if (empty($laboratorio)) {
    $errores[] = "Laboratorio requerido";
}
if (empty($tipo)) {
    $errores[] = "Tipo requerido";
}
if (strlen($descripcion) < 10) {
    $errores[] = "Descripción muy corta";
}

if (!empty($errores)) {
    $mensaje = implode(". ", $errores);
    header("Location: ../vistas/incidencia.php?error=" . urlencode($mensaje));
    exit;
}

$incidenciaDAO = new IncidenciaDAO();

try {
    $incidenciaDAO->registrar([
        "laboratorio" => $laboratorio,
        "tipo" => $tipo,
        "descripcion" => $descripcion,
        "empleado_id" => $empleado_id
    ]);
    header("Location: ../vistas/incidencia.php?ok=1");
    exit;
} catch (PDOException $e) {
    header("Location: ../vistas/incidencia.php?error=" . urlencode(
        "Error del servidor al registrar la incidencia."
    ));
    exit;
}


try {
    $resultado = $accesoDatosIncidencia->registrar([
        "laboratorio" => $laboratorio,
        "tipo" => $tipo,
        "descripcion" => $descripcion,
        "empleado_id" => $empleado_id
    ]);
} catch (PDOException $e) {
    header("Location: ../vistas/incidencia.php?error=" . urlencode(
        "Error del servidor. Intentá nuevamente más tarde."
    ));
    exit;
}

if ($resultado) {
    header("Location: ../vistas/incidencia.php?ok=1");
    exit;
}

header("Location: ../vistas/incidencia.php?error=" . urlencode("No se pudo registrar la incidencia"));
exit;