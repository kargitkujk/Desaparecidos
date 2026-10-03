<?php

class RespuestaJson
{
    public static function exito($datos = null, string $mensaje = "Operación exitosa", int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "status"  => "success",
            "mensaje" => $mensaje,
            "datos"   => $datos
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $mensaje, int $status = 500, $datos = null): void
    {
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "status"  => "error",
            "mensaje" => $mensaje,
            "datos"   => $datos
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
