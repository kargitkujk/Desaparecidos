<?php
require_once(dirname(__DIR__) . "/controlador/sesionYRoles.php");

verificarRol(["coordinador"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Incidencias</title>
</head>

<body>

<h1>Listado de Incidencias</h1>

<p>Acá se mostrarán las incidencias (pendiente de implementación).</p>

<br>
<a href="dashboard_coordinador.php">Volver</a>

</body>
</html>