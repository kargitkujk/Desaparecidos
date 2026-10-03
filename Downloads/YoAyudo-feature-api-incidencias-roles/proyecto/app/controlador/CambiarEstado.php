<?php
session_start();

require_once dirname(__DIR__) . "/controlador/SesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/UsuarioDAO.php";

/**
 * Alterna el estado (activo/inactivo) de un usuario.
 * Se accede mediante el botón "Cambiar Estado" de gestionUsuarios.php,
 * que envía el id por GET. Este controlador no ejecuta SQL directamente,
 * delega en UsuarioDAO.
 */

verificarRol(["coordinador"]);

$id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? (int) $_GET["id"] : null;

if (!$id) {
    header("Location: ../vistas/gestionUsuarios.php");
    exit;
}

$usuarioDAO = new UsuarioDAO();

try {
    $usuarioDAO->alternarEstado($id);
} catch (PDOException $e) {
    header("Location: ../vistas/gestionUsuarios.php?error=" . urlencode(
        "Error del servidor al cambiar el estado."
    ));
    exit;
}

header("Location: ../vistas/gestionUsuarios.php");
exit;
