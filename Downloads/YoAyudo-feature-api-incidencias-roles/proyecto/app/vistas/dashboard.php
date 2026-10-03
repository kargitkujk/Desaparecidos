<?php

require_once(dirname(__DIR__) . "/controlador/sesionYRoles.php");

/**
 * Panel principal de incidencias.
 * Permite a los usuarios con rol coordinador o técnico consultar
 * las incidencias registradas en el sistema y ver estadísticas
 * sobre su estado.
 */
verificarRol(["coordinador", "tecnico"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Incidencias</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="CSS_php/style.css">

    <script src="JS_php/coordinador.js"></script>
</head>

<body>

<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between">
        <h1 class="h4">Panel de Incidencias</h1>
        <a href="../controlador/logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>
    </div>
</header>

<nav class="bg-light border-bottom">
    <div class="container py-2">
        <a href="index.php" class="me-3">Inicio</a>
        <a href="incidencia.php" class="me-3">Nueva Incidencia</a>
        <a href="dashboard.php">Dashboard</a>
    </div>
</nav>

<main class="container-fluid my-4">

    <h2 class="mb-4">Panel de Incidencias</h2>

    <div class="row text-center mb-4">

        <div class="col-12 col-md-4">
            <div class="card shadow p-3">
                <h5>Total Incidencias</h5>
                <p id="totalIncidencias" class="fs-3 fw-bold">--</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow p-3">
                <h5>Pendientes</h5>
                <p id="pendientes" class="fs-3 fw-bold text-warning">--</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow p-3">
                <h5>Resueltas</h5>
                <p id="resueltas" class="fs-3 fw-bold text-success">--</p>
            </div>
        </div>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <h5 class="mb-3">Listado de Incidencias</h5>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Cédula</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    require_once dirname(__DIR__) . "/modelo/AccesoDatosIncidencia.php";

                    $accesoDatosIncidencia = new AccesoDatosIncidencia();
                    $resultado = $accesoDatosIncidencia->obtenerTodas();

                    foreach ($resultado as $row):
                    ?>

                        <tr>
                            <td><?= htmlspecialchars($row["cedula"]) ?></td>

                            <td><?= htmlspecialchars($row["descripcion"]) ?></td>

                            <td>
                                <span class="badge
                                    <?= $row["estado"] == "pendiente" ? "bg-warning" : "bg-success" ?>">
                                    <?= htmlspecialchars($row["estado"]) ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($row["fecha"]) ?></td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</main>

<footer class="bg-dark text-white text-center p-3 mt-5">
    © 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes e Incidencias
</footer>

<script>
fetch("../controlador/dashboardData.php")
.then(r => r.json())
.then(data => {
    document.getElementById("totalIncidencias").textContent = data.total;
    document.getElementById("pendientes").textContent = data.pendientes;
    document.getElementById("resueltas").textContent = data.resueltas;
});
</script>

</body>
</html>