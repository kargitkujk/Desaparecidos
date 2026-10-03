<?php

/**
 * Cierra la sesión del usuario actual.
 * Elimina las variables almacenadas en la sesión, destruye la sesión
 * y redirige al formulario de inicio de sesión.
 */

session_start();

session_unset();
session_destroy();

header("Location: ../vistas/Login.php");
exit;