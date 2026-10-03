<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/IncidenciaDAO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

if (!class_exists("AccesoDatosUsuario", false)) {
    class AccesoDatosUsuario
    {
        public function obtenerEmpleadoIdPorUsuario(int $usuarioId): ?int
        {
            return $usuarioId > 0 ? $usuarioId : null;
        }
    }
}

class IncidenciaController
{
    public function gestionar(string $metodo): void
    {
        $this->verificarSesion();
        try {
            switch ($metodo) {
                case "GET": $this->listar(); break;
                case "POST": $this->alta(); break;
                case "PUT": $this->modificarEstado(); break;
                case "DELETE": $this->baja(); break;
                default:
                    header("Allow: GET, POST, PUT, DELETE");
                    RespuestaJson::error("Método no permitido", 405);
            }
        } catch (PDOException $e) {
            RespuestaJson::error("Error del servidor al acceder a la base de datos", 500);
        }
    }

    private function listar(): void
    {
        $dao = new IncidenciaDAO();
        if (isset($_GET["id"])) {
            $valorId = $_GET["id"];
            if (!is_scalar($valorId)) {
                RespuestaJson::error("Id de incidencia inválido", 422);
                return;
            }

            $valorId = trim((string) $valorId);
            if (!preg_match('/^\d+$/', $valorId)) {
                RespuestaJson::error("Id de incidencia inválido", 422);
                return;
            }

            $id = (int) $valorId;
            $incidencia = $dao->obtenerPorId($id);
            if ($incidencia === null) {
                RespuestaJson::error("Incidencia no encontrada", 404);
                return;
            }

            RespuestaJson::exito(200, "Incidencia encontrada");
            return;
        }

        $accion = $_GET["accion"] ?? "todas";
        if (is_array($accion)) {
            RespuestaJson::error("Acción GET no reconocida", 400);
            return;
        }
        $accion = (string) $accion;

        switch ($accion) {
            case "estadisticas": RespuestaJson::exito( $dao->obtenerEstadisticas(), 200, "Estadísticas obtenidas"); break;
            case "laboratorios": RespuestaJson::exito($dao->contarPorLaboratorio(), 200, "Conteo por laboratorio"); break;
            case "tipos": RespuestaJson::exito( $dao->contarPorTipo(), 200, "Conteo por tipo"); break;
            case "todas": RespuestaJson::exito( $dao->listarTodas(), 200, "Listado completo"); break;
            default: RespuestaJson::error("Acción GET no reconocida", 400); return;
        }
    }

  private function alta(): void
{
    $datos = $this->leerJson();

    $laboratorio = trim($datos["laboratorio"] ?? "");
    $tipo = trim($datos["tipo"] ?? "");
    $descripcion = trim($datos["descripcion"] ?? "");

    if ($laboratorio === "" || $tipo === "" || $descripcion === "") {
        RespuestaJson::error("Laboratorio, tipo y descripción son obligatorios", 422);
    }

    if (strlen($descripcion) < 10) {
        RespuestaJson::error("La descripción debe tener al menos 10 caracteres", 422);
    }

    $usuarioDAO = new AccesoDatosUsuario();
    $usuarioId = $_SESSION["usuario_id"] ?? 0;
    $empleadoId = $usuarioDAO->obtenerEmpleadoIdPorUsuario((int) $usuarioId);

    if (!$empleadoId) {
        RespuestaJson::error("No hay un empleado asociado al usuario", 422);
    }

    $dao = new IncidenciaDAO();
    $resultado = $dao->registrar([
        "laboratorio" => $laboratorio,
        "tipo"        => $tipo,
        "descripcion" => $descripcion,
        "empleado_id" => $empleadoId
    ]);

    if (!$resultado) {
        RespuestaJson::error("No se pudo registrar la incidencia", 400);
    }

    RespuestaJson::exito(201, "Incidencia registrada correctamente");
}


    private function modificarEstado(): void
    {
        $datos = $this->leerJson();
        $id = filter_var($datos["id"] ?? null, FILTER_VALIDATE_INT);
        $estado = trim($datos["estado"] ?? "");
        if (!$id) RespuestaJson::error("Id de incidencia inválido", 422);
        if (!in_array($estado, ["pendiente", "resuelta"], true)) RespuestaJson::error("Estado inválido", 422);
        $dao = new IncidenciaDAO();
        if ($dao->obtenerPorId($id) === null) RespuestaJson::error("Incidencia no encontrada", 404);
        if (!$dao->actualizarEstado($id, $estado)) RespuestaJson::error("No se pudo actualizar la incidencia", 400);
        RespuestaJson::exito(200, "Estado actualizado correctamente");
    }

    private function baja(): void
    {
        $datos = $this->leerJson(false);
        $id = filter_var($datos["id"] ?? $_GET["id"] ?? null, FILTER_VALIDATE_INT);
        if (!$id) RespuestaJson::error("Falta un id de incidencia válido", 422);
        $dao = new IncidenciaDAO();
        if ($dao->obtenerPorId($id) === null) RespuestaJson::error("Incidencia no encontrada", 404);
        if (!$dao->eliminar($id)) RespuestaJson::error("No se pudo eliminar la incidencia", 400);
        RespuestaJson::exito(200, "Incidencia eliminada correctamente");
    }

    private function leerJson(bool $obligatorio = true): array
    {
        $contenido = file_get_contents("php://input");
        if ($contenido === "" && !$obligatorio) return [];
        $datos = json_decode($contenido, true);
        if (!is_array($datos)) RespuestaJson::error("El cuerpo debe contener JSON válido", 400);
        return $datos;
    }

    private function verificarSesion(): void
    {
        if (!isset($_SESSION["usuario_id"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
    }
}