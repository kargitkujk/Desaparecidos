<?php
class ConectorPDO
{
    public static function conectar(): PDO
    {
        $config = require __DIR__ . "/../../config/config.php";

        try {
            $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NOMBRE']};charset=utf8mb4";
            $conexion = new PDO($dsn, $config['DB_USUARIO'], $config['DB_CLAVE']);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
