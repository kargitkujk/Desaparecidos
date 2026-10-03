<?php

session_start();

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/AccesoDatosRol.php";

/**
 * Formulario de registro de nuevos usuarios.
 * Solo accesible para coordinadores. Muestra los errores de validación 
 * de un intento anterior, si los hubiera, y conserva
 * los datos ingresados para no obligar a completarlos de nuevo.
 */

verificarRol(["coordinador"]);

$accesoDatosRol = new AccesoDatosRol();
$roles = $accesoDatosRol->listarTodos();

$errores = $_SESSION["registro_errores"] ?? [];
$datos = $_SESSION["registro_datos"] ?? [
    "cedula" => "",
    "nombre" => "",
    "apellido" => "",
    "email" => "",
    "rol" => ""
];

unset($_SESSION["registro_errores"]);
unset($_SESSION["registro_datos"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registro de Usuario</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="CSS_php/style.css">

</head>

<body>

<header class="bg-dark text-white p-3">

<div class="container d-flex justify-content-between">

<h2>Registro de Usuario</h2>

<a href="tecnicos.php" class="btn btn-light">
Volver
</a>

</div>

</header>

<div class="container mt-4">

<div class="card shadow">

<div class="card-body">

<?php if (!empty($errores)): ?>

<div class="alert alert-danger">

<strong>Se encontraron los siguientes errores:</strong>

<ul class="mb-0">

<?php foreach ($errores as $error): ?>
<li><?= htmlspecialchars($error) ?></li>
<?php endforeach; ?>

</ul>

</div>

<?php endif; ?>

<form action="../controlador/procesarRegistro.php" method="POST">

<div class="mb-3">

<label class="form-label">Cédula</label>

<input
type="text"
class="form-control"
name="cedula"
pattern="[0-9]{7,8}"
value="<?= htmlspecialchars($datos["cedula"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Nombre</label>

<input
type="text"
class="form-control"
name="nombre"
value="<?= htmlspecialchars($datos["nombre"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Apellido</label>

<input
type="text"
class="form-control"
name="apellido"
value="<?= htmlspecialchars($datos["apellido"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Email</label>

<input
type="email"
class="form-control"
name="email"
value="<?= htmlspecialchars($datos["email"]) ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Contraseña</label>

<input
type="password"
class="form-control"
name="contrasena"
minlength="8"
required>

<div class="form-text">Mínimo 8 caracteres.</div>

</div>

<div class="mb-3">

<label class="form-label">Confirmar contraseña</label>

<input
type="password"
class="form-control"
name="confirmacion"
minlength="8"
required>

</div>

<div class="mb-4">

<label class="form-label">Rol</label>

<select name="rol" class="form-select" required>

<option value="">Seleccionar</option>

<?php foreach ($roles as $rol): ?>

<option
value="<?= $rol["id"] ?>"
<?= $datos["rol"] == $rol["id"] ? "selected" : "" ?>>

<?= htmlspecialchars($rol["nombre"]) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<button type="submit" class="btn btn-success">
Registrar usuario
</button>

<a href="tecnicos.php" class="btn btn-secondary">
Cancelar
</a>

</form>

</div>

</div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
© 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes e Incidencias
</footer>

</body>

</html>