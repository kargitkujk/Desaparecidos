<?php
/**
 * Punto único de configuración del sistema.
 * Define constantes con las rutas absolutas de cada capa (modelo, controlador,
 * vista) y devuelve un array con las credenciales de la base de datos.
 */

define("RUTA_RAIZ", dirname(__DIR__));
define("RUTA_APP", RUTA_RAIZ . "/app");
define("RUTA_MODELO", RUTA_APP . "/modelo");
define("RUTA_CONTROLADOR", RUTA_APP . "/controlador");
define("RUTA_VISTA", RUTA_APP . "/vista");

return [
    "DB_HOST"    => "127.0.0.1",
    "DB_NOMBRE"  => "yoayudo",
    "DB_USUARIO" => "root",
    "DB_CLAVE"   => ""
];
