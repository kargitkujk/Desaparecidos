<?php

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";
require_once dirname(__DIR__) . "/modelo/AccesoDatosIncidencia.php";

verificarRol(["coordinador"]);

$accesoDatosIncidencia = new AccesoDatosIncidencia();

$estadisticas = $accesoDatosIncidencia->obtenerEstadisticas();
$total = $estadisticas["total"];
$pendientes = $estadisticas["pendientes"];
$resueltas = $estadisticas["resueltas"];

$laboratorios = $accesoDatosIncidencia->contarPorLaboratorio();
$tipos = $accesoDatosIncidencia->contarPorTipo();
$ultimas = $accesoDatosIncidencia->obtenerUltimas(10);

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reportes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="CSS_php/style.css">

</head>

<body>

<header class="bg-dark text-white p-3">

<div class="container d-flex justify-content-between">

<h2>Reportes</h2>

<a href="dashboard.php" class="btn btn-light">

Volver al Dashboard

</a>

</div>

</header>

<div class="container mt-4">

<div class="row text-center mb-4">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h5>Total</h5>

<p class="display-5">

<?= $total ?>

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h5>Pendientes</h5>

<p class="display-5 text-warning">

<?= $pendientes ?>

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h5>Resueltas</h5>

<p class="display-5 text-success">

<?= $resueltas ?>

</p>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Incidencias por laboratorio

</div>

<div class="card-body">

<table class="table">

<thead>

<tr>

<th>Laboratorio</th>

<th>Cantidad</th>

</tr>

</thead>

<tbody>

<?php foreach($laboratorios as $lab): ?>

<tr>

<td><?= htmlspecialchars($lab["laboratorio"]) ?></td>

<td><?= $lab["cantidad"] ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Incidencias por tipo

</div>

<div class="card-body">

<table class="table">

<thead>

<tr>

<th>Tipo</th>

<th>Cantidad</th>

</tr>

</thead>

<tbody>

<?php foreach($tipos as $tipo): ?>

<tr>

<td><?= htmlspecialchars($tipo["tipo"]) ?></td>

<td><?= $tipo["cantidad"] ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header">

Últimas incidencias

</div>

<div class="card-body">

<table class="table table-striped">

<thead>

<tr>

<th>Cédula</th>

<th>Descripción</th>

<th>Estado</th>

<th>Fecha</th>

</tr>

</thead>

<tbody>

<?php foreach($ultimas as $i): ?>

<tr>

<td><?= htmlspecialchars($i["cedula"]) ?></td>

<td><?= htmlspecialchars($i["descripcion"]) ?></td>

<td><?= htmlspecialchars($i["estado"]) ?></td>

<td><?= htmlspecialchars($i["fecha"]) ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<footer class="bg-dark text-white text-center p-3 mt-5">

© 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes e Incidencias

</footer>

</body>

</html>