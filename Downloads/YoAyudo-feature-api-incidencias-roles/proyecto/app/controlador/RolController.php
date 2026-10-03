<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/RolDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/**
 * Controlador único de la API de roles.
 * Recibe las peticiones HTTP mediante gestionar() y delega
 * la persistencia en RolDAO.
 */
class RolController
{
    public function gestionar(string $metodo): void
    {
        $this->verificarCoordinador();

        try {
            switch ($metodo) {
                case "GET":
                    $this->listar();
                    break;
                case "PUT":
                    $this->alta();
                    break;
                case "PATCH":
                    $this->modificar();
                    break;
                case "DELETE":
                    $this->baja();
                    break;
                default:
                    header("Allow: GET, PUT, PATCH, DELETE");
                    RespuestaJson::error("Método no permitido", 405);
            }
        } catch (PDOException $e) {
            if (($e->errorInfo[0] ?? "") === "23000") {
                RespuestaJson::error("La operación viola una restricción de integridad", 409);
            }

            RespuestaJson::error("Error del servidor al acceder a la base de datos", 500);
        }
    }

    private function listar(): void
    {
        $dao = new RolDAO();

        if (isset($_GET["id"])) {
            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

            if (!$id) {
                RespuestaJson::error("Id de rol inválido", 422);
            }

            $rol = $dao->buscarPorId((int) $id);

            if ($rol === null) {
                RespuestaJson::error("Rol no encontrado", 404);
            }

            RespuestaJson::exito($rol);
        }

        RespuestaJson::exito($dao->listarTodos());
    }
    private function alta(): void
    {
        $datos = $this->leerJson();
        $nombre = strtolower(trim($datos["nombre"] ?? ""));

        if ($nombre === "") {
            RespuestaJson::error("El nombre del rol es obligatorio", 422);
        }

        $dao = new RolDAO();

        if ($dao->buscarPorNombre($nombre) !== null) {
            RespuestaJson::error("Ya existe un rol con ese nombre", 409);
        }

        if (!$dao->registrar($nombre)) {
            RespuestaJson::error("No se pudo registrar el rol", 400);
        }

        RespuestaJson::exito(["mensaje" => "Rol registrado correctamente"], 201);
    }

    private function modificar(): void
    {
        $datos = $this->leerJson();
        $id = filter_var($datos["id"] ?? null, FILTER_VALIDATE_INT);
        $nombre = strtolower(trim($datos["nombre"] ?? ""));

        if (!$id || $nombre === "") {
            RespuestaJson::error("Id y nombre de rol son obligatorios", 422);
        }

        $dao = new RolDAO();

        if ($dao->buscarPorId((int) $id) === null) {
            RespuestaJson::error("Rol no encontrado", 404);
        }

        $rolMismoNombre = $dao->buscarPorNombre($nombre);

        if ($rolMismoNombre !== null && (int) $rolMismoNombre["id"] !== (int) $id) {
            RespuestaJson::error("Ya existe otro rol con ese nombre", 409);
        }

        if (!$dao->actualizar((int) $id, $nombre)) {
            RespuestaJson::error("No se realizaron cambios en el rol", 400);
        }

        RespuestaJson::exito(["mensaje" => "Rol actualizado correctamente", "code" => 201]);
    }

    private function baja(): void
    {
        $datos = $this->leerJson(false);
        $id = filter_var(
            $datos["id"] ?? $_GET["id"] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            RespuestaJson::error("Falta un id de rol válido", 422);
        }

        $dao = new RolDAO();

        if ($dao->buscarPorId((int) $id) === null) {
            RespuestaJson::error("Rol no encontrado", 404);
        }

        if (!$dao->eliminar((int) $id)) {
            RespuestaJson::error("No se pudo eliminar el rol", 400);
        }

        RespuestaJson::exito(["mensaje" => "Rol eliminado correctamente"]);
    }

    private function leerJson(bool $obligatorio = true): array
    {
        $contenido = file_get_contents("php://input");

        if ($contenido === "" && !$obligatorio) {
            return [];
        }

        $datos = json_decode($contenido, true);

        if (!is_array($datos)) {
            RespuestaJson::error("El cuerpo debe contener JSON válido", 400);
        }

        return $datos;
    }

    private function verificarCoordinador(): void
    {
        if (!isset($_SESSION["usuario_id"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }

        $roles = $_SESSION["roles"] ?? [];

        if (!in_array("coordinador", $roles, true) && !in_array("administrador", $roles, true)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
    }
}