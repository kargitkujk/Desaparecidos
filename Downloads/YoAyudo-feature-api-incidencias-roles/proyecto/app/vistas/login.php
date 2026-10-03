<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - YoAyudo</title>
    <link rel="stylesheet" href="CSS_php/style.css">
</head>

<body>

<header>
    <h1>YoAyudo</h1>
</header>

<section>
    <h2>Inicio de Sesión</h2>

    <?php if (isset($_GET["error"])): ?>
        <p class="error">
        <?php
            switch ($_GET["error"]) {
                case "credenciales":
                    echo "Usuario o contraseña incorrectos";
                    break;
                case "inactivo":
                    echo "El usuario está inactivo";
                    break;
                case "sin_roles":
                    echo "El usuario no tiene roles asignados";
                    break;
                case "sin_sesion":
                    echo "Debe iniciar sesión primero";
                    break;
                case "sin_permiso":
                    echo "No tiene permiso para acceder";
                    break;
                case "servidor":
                    echo "Error del servidor. Intentá nuevamente más tarde.";
                    break;
                default:
                    echo "Error desconocido";
            }
        ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="../controlador/procesarLogin.php">

        <div class="form-group">
            <label for="cedula">Cédula:</label>
            <input type="text" name="cedula" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>
        </div>

        <button type="submit">Iniciar Sesión</button>

    </form>
</section>

<footer>
    © 2026 YoAyudo - Plataforma de Gestión y Seguimiento de Solicitudes de Incidencias
</footer>

</body>
</html>