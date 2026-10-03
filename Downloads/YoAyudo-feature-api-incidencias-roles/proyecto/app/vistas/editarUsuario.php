<?php

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/AccesoDatosUsuario.php";
require_once dirname(__DIR__) . "/modelo/AccesoDatosRol.php";

verificarRol(["coordinador"]);

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: tecnicos.php");
    exit;
}

$id = (int) $_GET["id"];

$accesoDatosUsuario = new AccesoDatosUsuario();
$usuario = $accesoDatosUsuario->buscarPorId($id);

if (!$usuario) {
    header("Location: tecnicos.php");
    exit;
}

$rolActual = $accesoDatosUsuario->obtenerRolIdDeUsuario($id);

$accesoDatosRol = new AccesoDatosRol();
$roles = $accesoDatosRol->listarTodos();

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar Usuario</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="CSS_php/style.css">

</head>

<body>

<header class="bg-dark text-white p-3">

<div class="container d-flex justify-content-between">

<h2>Editar Usuario</h2>

<a href="tecnicos.php" class="btn btn-light">

Volver

</a>

</div>

</header>

<div class="container mt-4">

<div class="card shadow">

<div class="card-body">

<form action="../controlador/guardarUsuario.php" method="POST">

<input type="hidden" name="id" value="<?= $usuario["id"] ?>">

<div class="mb-3">

<label class="form-label">

Cédula

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($usuario["cedula"]) ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Nombre

</label>

<input
type="text"
class="form-control"
name="nombre"
value="<?= htmlspecialchars($usuario["nombre"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Apellido

</label>

<input
type="text"
class="form-control"
name="apellido"
value="<?= htmlspecialchars($usuario["apellido"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Estado

</label>

<select
name="estado"
class="form-select">

<option
value="activo"
<?= $usuario["estado"]=="activo" ? "selected" : "" ?>>

Activo

</option>

<option
value="inactivo"
<?= $usuario["estado"]=="inactivo" ? "selected" : "" ?>>

Inactivo

</option>

</select>

</div>

<div class="mb-4">

<label class="form-label">

Rol

</label>

<select
name="rol"
class="form-select">

<?php foreach($roles as $rol): ?>

<option
value="<?= $rol["id"] ?>"
<?= $rolActual==$rol["id"] ? "selected" : "" ?>>

<?= htmlspecialchars($rol["nombre"]) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<button
type="submit"
class="btn btn-success">

Guardar cambios

</button>

<a
href="tecnicos.php"
class="btn btn-secondary">

Cancelar

</a>

</form>

</div>

</div>

</div>

</body>

</html>