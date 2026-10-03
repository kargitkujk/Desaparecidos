<?php

function verificarSesion() {
    if (!isset($_SESSION["cedula"])) {
        header("Location: ../vistas/login.php?error=sin_sesion");
        exit();
    }
}