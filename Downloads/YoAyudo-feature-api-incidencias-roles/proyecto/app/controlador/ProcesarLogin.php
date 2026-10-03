<?php
session_start();

require_once dirname(__DIR__) . "/modelo/UsuarioDAO.php";

/**
 * Procesa las credenciales enviadas desde el formulario de inicio de sesión.
 * Este controlador recibe la cédula y contraseña con POST, usa UsuarioDAO
 * para autenticar al usuario, inicia la sesión y redirige al panel que corresponda según los roles.
 */

$usuarioDAO = new UsuarioDAO();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../vistas/login.php");
    exit;
}

$cedula     = trim($_POST["cedula"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";

try {
    $usuario = $usuarioDAO->buscarPorCedula($cedula);
} catch (PDOException $e) {
    header("Location: ../vistas/login.php?error=servidor");
    exit;
}

if (!$usuario || !password_verify($contrasena, $usuario["contrasena"])) {
    header("Location: ../vistas/login.php?error=credenciales");
    exit;
}

$_SESSION["usuario_id"] = $usuario["id"];
$_SESSION["cedula"]     = $usuario["cedula"];
$_SESSION["nombre"]     = $usuario["nombre"];
$_SESSION["roles"]      = $usuario["roles"];

$esCoordinador = in_array("coordinador", $usuario["roles"]);
$esTecnico     = in_array("tecnico", $usuario["roles"]);
$esDocente     = in_array("docente", $usuario["roles"]);

if ($esCoordinador && $esTecnico) {
    header("Location: ../vistas/dashboard.php");
    exit;
}

if ($esCoordinador) {
    header("Location: ../vistas/dashboard_coordinador.php");
    exit;
}

if ($esTecnico) {
    header("Location: ../vistas/dashboard_tecnico.php");
    exit;
}

if ($esDocente) {
    header("Location: ../vistas/dashboard_docente.php");
    exit;
}

header("Location: ../vistas/login.php?error=sin_roles");
exit;
