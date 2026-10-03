<?php

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/AccesoDatosUsuario.php";

verificarRol(["coordinador"]);

$accesoDatosUsuario = new AccesoDatosUsuario();
$usuarios = $accesoDatosUsuario->listarTodos();

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Gestión de Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="CSS_php/style.css">

</head>

<body>

<header class="bg-dark text-white p-3">

<div class="container d-flex justify-content-between align-items-center">

<h2>Gestión de Usuarios</h2>

<div>

<a href="dashboard.php" class="btn btn-light me-2">
Dashboard
</a>

<a href="../controlador/logout.php" class="btn btn-danger">
Cerrar sesión
</a>

</div>

</div>

</header>

<div class="container mt-4">

<?php if (isset($_GET["registro"]) && $_GET["registro"] == "ok"): ?>

<div class="alert alert-success">
Usuario registrado correctamente.
</div>

<?php endif; ?>

<?php if (isset($_GET["error"])): ?>

<div class="alert alert-danger">
<?= htmlspecialchars($_GET["error"]) ?>
</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

Usuarios registrados

<a href="registro.php" class="btn btn-primary btn-sm">
Nuevo usuario
</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-striped table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Cédula</th>

<th>Nombre</th>

<th>Apellido</th>

<th>Estado</th>

<th>Roles</th>

<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach($usuarios as $usuario): ?>

<tr>

<td><?= htmlspecialchars($usuario["id"]) ?></td>

<td><?= htmlspecialchars($usuario["cedula"]) ?></td>

<td><?= htmlspecialchars($usuario["nombre"]) ?></td>

<td><?= htmlspecialchars($usuario["apellido"]) ?></td>

<td>

<?php if($usuario["estado"]=="activo"): ?>

<span class="badge bg-success">

Activo

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactivo

</span>

<?php endif; ?>

</td>

<td><?= htmlspecialchars($usuario["roles"]) ?></td>

<td>

<a
href="editarUsuario.php?id=<?= $usuario["id"] ?>"
class="btn btn-primary btn-sm">

Editar

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">

© 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes e Incidencias

</footer>

</body>

</html>