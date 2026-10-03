<?php

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";

/**
 * Panel para usuarios con rol técnico.
 * Permite al técnico acceder a la lista de incidencias
 * y cerrar su sesión.
 */
verificarRol(["tecnico"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel Técnico</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Panel Técnico</h2>

<p>Bienvenido, <?php echo $_SESSION["nombre"]; ?></p>

<div class="list-group">

<a href="dashboard.php" class="list-group-item list-group-item-action">
Ver Incidencias
</a>

<a href="../controlador/logout.php" class="list-group-item list-group-item-danger">
Cerrar sesión
</a>

</div>

</div>

</body>

</html>