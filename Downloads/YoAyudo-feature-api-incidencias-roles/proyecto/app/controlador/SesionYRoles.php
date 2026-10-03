<?php

/**
 * Funciones de control de acceso usadas por los controladores y por las
 * vistas que requieren sesión iniciada y/o un rol específico.
 * No forman una capa aparte: son funciones auxiliares que cada
 * archivo protegido incluye directamente antes de ejecutar su lógica.
 */

/**
 * Comprueba que exista una sesión de usuario iniciada.
 * Inicia la sesión si todavía no hay una activa. Si no hay un usuario
 * autenticado, redirige al formulario de login.
 *
 * @return void
 */
function verificarSesion()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["usuario_id"])) {
        header("Location: ../vista/login.php?error=sin_sesion");
        exit;
    }
}

/**
 * Verifica que el usuario tenga sesión iniciada y al menos uno de los
 * roles permitidos. Si no cumple alguna de las dos condiciones, redirige
 * al login.
 *
 * @param array $rolesPermitidos Lista de roles autorizados para acceder.
 * @return void
 */
function verificarRol(array $rolesPermitidos)
{
    verificarSesion();

    if (!isset($_SESSION["roles"])) {
        header("Location: ../vista/login.php?error=sin_permiso");
        exit;
    }

    foreach ($rolesPermitidos as $rol) {
        if (in_array($rol, $_SESSION["roles"])) {
            return;
        }
    }

    header("Location: ../vista/login.php?error=sin_permiso");
    exit;
}
