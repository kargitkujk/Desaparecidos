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

</head>

<body>

<div class="container mt-5">

<h2>Gestión de Usuarios</h2>

<?php if (isset($_GET["error"])): ?>

<div class="alert alert-danger">
<?= htmlspecialchars($_GET["error"]) ?>
</div>

<?php endif; ?>

<p>Coordinador:
<strong><?php echo $_SESSION["nombre"]; ?></strong>
</p>

<table class="table table-striped table-hover">

<thead class="table-dark">

<tr>

<th>Cédula</th>

<th>Nombre</th>

<th>Apellido</th>

<th>Estado</th>

<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach ($usuarios as $usuario): ?>

<tr>

<td><?= htmlspecialchars($usuario["cedula"]) ?></td>

<td><?= htmlspecialchars($usuario["nombre"]) ?></td>

<td><?= htmlspecialchars($usuario["apellido"]) ?></td>

<td>

<?php if ($usuario["estado"] == "activo"): ?>

<span class="badge bg-success">

Activo

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactivo

</span>

<?php endif; ?>

</td>

<td>

<a href="../controlador/cambiarEstado.php?id=<?= $usuario["id"] ?>" class="btn btn-warning btn-sm">

Cambiar Estado

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<a href="dashboard.php" class="btn btn-primary">

Volver al Dashboard

</a>

</div>

</body>

</html>