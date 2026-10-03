<?php
session_start();

require_once dirname(__DIR__) . "/controlador/SesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/UsuarioDAO.php";

/**
 * Procesa la edición de un técnico/usuario desde el panel de administración.
 * Recibe los datos por POST y delega la actualización (datos básicos +
 * reasignación de rol) a UsuarioDAO. Este controlador no ejecuta SQL directamente.
 */

verificarRol(["coordinador"]);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../vista/tecnicos.php");
    exit;
}

$id       = (int) $_POST["id"];
$nombre   = trim($_POST["nombre"]);
$apellido = trim($_POST["apellido"]);
$estado   = $_POST["estado"];
$rol      = (int) $_POST["rol"];

$usuarioDAO = new UsuarioDAO();

try {
    $usuarioDAO->actualizarUsuarioYRol($id, $nombre, $apellido, $estado, $rol);
} catch (PDOException $e) {
    header("Location: ../vista/tecnicos.php?error=" . urlencode(
        "Error del servidor al actualizar el usuario."
    ));
    exit;
}

header("Location: ../vista/tecnicos.php");
exit;
