<?php

session_start();

require_once(dirname(__DIR__) . "/controlador/sesionYRoles.php");

/**
 * Panel principal de administración.
 * Permite a los usuarios con rol administrador acceder a las
 * funcionalidades de gestión de usuarios, consulta de incidencias
 * y generación de reportes.
 */
verificarRol(["administrador"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel Administrador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="CSS_php/style.css">

</head>

<body style="background:#b8b8b8;">

<header class="shadow" style="background:#6ec1f2;">

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h1 class="text-white fw-bold mb-0">
                    SGRSI
                </h1>

                <small class="text-white">
                    Sistema de Gestión de Recursos y Soporte de Informática
                </small>

            </div>

            <a href="../controlador/logout.php" class="btn btn-danger">
                Cerrar sesión
            </a>

        </div>

        <nav class="mt-4 p-3 rounded" style="background:#707070;">

            <div class="text-center">

                <a href="index.php" class="text-white text-decoration-none mx-3">
                    Inicio
                </a>

                <a href="incidencia.php" class="text-white text-decoration-none mx-3">
                    Incidencia
                </a>

                <a href="dashboard_admin.php" class="text-white text-decoration-none mx-3">
                    Administrador
                </a>

            </div>

        </nav>

    </div>

</header>

<div class="container my-5">

    <div class="text-center mb-5">

        <h2 class="fw-bold">
            Panel de Administración
        </h2>

        <p class="fs-5">

            Bienvenido,

            <strong>
                <?php echo htmlspecialchars($_SESSION["nombre"]); ?>
            </strong>

        </p>

    </div>

    <div class="row g-4">

        <div class="col-md-4">

            <div class="card shadow border-0 h-100">

                <div class="card-header text-center text-white fw-bold"
                    style="background:#5bb5ee;">

                    Gestión de Usuarios

                </div>

                <div class="card-body text-center">

                    <p>
                        Alta, edición y administración de usuarios del sistema.
                    </p>

                    <a href="tecnicos.php"
                       class="btn text-white"
                       style="background:#5bb5ee;">

                        Ingresar

                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0 h-100">

                <div class="card-header text-center text-white fw-bold"
                    style="background:#5bb5ee;">

                    Incidencias

                </div>

                <div class="card-body text-center">

                    <p>
                        Visualizar todas las incidencias registradas y realizar su seguimiento.
                    </p>

                    <a href="dashboard.php"
                       class="btn text-white"
                       style="background:#5bb5ee;">

                        Ingresar

                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0 h-100">

                <div class="card-header text-center text-white fw-bold"
                    style="background:#5bb5ee;">

                    Reportes

                </div>

                <div class="card-body text-center">

                    <p>
                        Consultar estadísticas e indicadores del sistema.
                    </p>

                    <a href="reportes.php"
                       class="btn text-white"
                       style="background:#5bb5ee;">

                        Ingresar

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<footer class="mt-5">

    <div class="container">

        <div class="rounded p-3 text-center text-white shadow"
             style="background:#8cc8f3;">

            © 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes e Incidencias

        </div>

    </div>

</footer>

</body>

</html>