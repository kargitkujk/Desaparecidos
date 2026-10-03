<?php

require_once dirname(__DIR__) . "/controlador/sesionYRoles.php";

/**
 * Panel destinado a usuarios con rol docente.
 * Permite al usuario docente acceder al sistema y cerrar su sesión.
 * Actualmente no tiene funcionalidades específicas.
 */
verificarRol(["docente"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel Docente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <h2>Panel Docente</h2>

    <p>
        Bienvenido/a,
        <strong><?php echo $_SESSION["nombre"]; ?></strong>.
    </p>

    <div class="alert alert-info mt-4">

        Su sesión fue iniciada correctamente.

        <br>

        Actualmente no tenés funciones específicas asignadas dentro del sistema.

    </div>

    <a href="incidencia.php" class="btn btn-primary">
        Reportar incidencia
    </a>

    <a href="../controlador/logout.php" class="btn btn-danger">
        Cerrar sesión
    </a>

</div>

</body>

</html>