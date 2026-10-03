<?php
require_once __DIR__ . '/../../config/config.php';
require_once RUTA_MODELO . '/ConectorPDO.php';
require_once RUTA_MODELO . '/PrestamoDAO.php';
require_once RUTA_VISTA . '/RespuestaJson.php';

class PrestamoController {

    private PrestamoDAO $prestamoDAO;

    public function __construct() {
        $this->prestamoDAO = new PrestamoDAO();
    }

    public function crearPrestamo(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $id_usuario = $data['id_usuario'] ?? null;
        $monto = $data['monto'] ?? null;
        $fecha = $data['fecha'] ?? null;

        if (!$id_usuario || !$monto || !$fecha) {
            RespuestaJson::error("Faltan datos obligatorios", 422);
        }

        if ($this->prestamoDAO->crearPrestamo($id_usuario, $monto, $fecha)) {
            RespuestaJson::exito(null, "Préstamo creado exitosamente", 201);
        } else {
            RespuestaJson::error("Error al crear el préstamo", 500);
        }
    }

    public function obtenerHistorialPorUsuario(int $id_usuario): void {
        $prestamos = $this->prestamoDAO->obtenerHistorialPorUsuario($id_usuario);

        if (empty($prestamos)) {
            RespuestaJson::error("No se encontraron préstamos para el usuario especificado", 404);
        }

        RespuestaJson::exito($prestamos, "Historial de préstamos obtenido", 200);
    }

    public function cerrarPrestamo(int $id): void {
        if ($this->prestamoDAO->cerrarPrestamo($id)) {
            RespuestaJson::exito(null, "Préstamo cerrado exitosamente", 200);
        } else {
            RespuestaJson::error("Préstamo no encontrado", 404);
        }
    }
}
