<?php
session_start();

require_once dirname(__DIR__) . "/controlador/SesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/UsuarioDAO.php";
require_once dirname(__DIR__) . "/modelo/Registro.php";

verificarRol(["coordinador"]);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../vista/registro.php");
    exit;
}

$cedula       = trim($_POST["cedula"] ?? "");
$nombre       = trim($_POST["nombre"] ?? "");
$apellido     = trim($_POST["apellido"] ?? "");
$email        = trim($_POST["email"] ?? "");
$contrasena   = $_POST["contrasena"] ?? "";
$confirmacion = $_POST["confirmacion"] ?? "";
$rolId        = (int) ($_POST["rol"] ?? 0);

$registro   = new Registro();
$usuarioDAO = new UsuarioDAO();

try {
    $errores = $registro->validar(
        $cedula,
        $nombre,
        $apellido,
        $email,
        $contrasena,
        $confirmacion,
        $rolId
    );
} catch (PDOException $e) {
    $_SESSION["registro_errores"] = ["Error del servidor. Intentá nuevamente más tarde."];
    $_SESSION["registro_datos"] = compact("cedula","nombre","apellido","email","rolId");
    header("Location: ../vista/registro.php");
    exit;
}

if (!empty($errores)) {
    $_SESSION["registro_errores"] = $errores;
    $_SESSION["registro_datos"] = compact("cedula","nombre","apellido","email","rolId");
    header("Location: ../vista/registro.php");
    exit;
}

// Aquí usamos directamente el DAO para registrar
$resultado = $usuarioDAO->registrarUsuario(
    $cedula,
    $nombre,
    $apellido,
    $email,
    $contrasena,
    $rolId
);

if (!$resultado["ok"]) {
    $_SESSION["registro_errores"] = [$resultado["error"]];
    header("Location: ../vista/registro.php");
    exit;
}

header("Location: ../vista/tecnicos.php?registro=ok");
exit;
