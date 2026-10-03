<?php

require_once __DIR__ . "/ConectorPDO.php";

/**
 * DAO que centraliza todas las operaciones de persistencia
 * relacionadas con las incidencias.
 */
class IncidenciaDAO
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = ConectorPDO::conectar();
    }

    public function listarTodas(): array
    {
        $sql = "SELECT
                    i.id,
                    u.cedula,
                    i.descripcion,
                    i.laboratorio,
                    i.tipo,
                    i.estado,
                    i.fecha,
                    i.empleado_id
                FROM incidencias i
                LEFT JOIN empleados e ON i.empleado_id = e.id
                LEFT JOIN usuarios u ON e.usuario_id = u.id
                ORDER BY i.fecha DESC";

        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT
                    i.id,
                    u.cedula,
                    i.descripcion,
                    i.laboratorio,
                    i.tipo,
                    i.estado,
                    i.fecha,
                    i.empleado_id
                FROM incidencias i
                LEFT JOIN empleados e ON i.empleado_id = e.id
                LEFT JOIN usuarios u ON e.usuario_id = u.id
                WHERE i.id = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);
        $incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

        return $incidencia ?: null;
    }

    public function registrar(array $datos): bool
    {
    try {
        $sql = "INSERT INTO incidencias
                (descripcion, fecha, estado, laboratorio, tipo, empleado_id)
                VALUES
                (:descripcion, NOW(), 'pendiente', :laboratorio, :tipo, :empleado_id)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ":descripcion" => $datos["descripcion"],
            ":laboratorio" => $datos["laboratorio"],
            ":tipo" => $datos["tipo"],
            ":empleado_id" => $datos["empleado_id"]
        ]);
    } catch (PDOException $e) {
        return false;
    }
  }


    public function actualizarEstado(int $id, string $estado): bool
    {
        $sql = "UPDATE incidencias
                SET estado = :estado
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":estado" => $estado,
            ":id" => $id
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM incidencias WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->rowCount() > 0;
    }

    public function obtenerEstadisticas(): array
    {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(estado = 'pendiente') AS pendientes,
                    SUM(estado = 'resuelta') AS resueltas
                FROM incidencias";

        $resultado = $this->conexion->query($sql)->fetch(PDO::FETCH_ASSOC);

        return [
            "total" => (int) ($resultado["total"] ?? 0),
            "pendientes" => (int) ($resultado["pendientes"] ?? 0),
            "resueltas" => (int) ($resultado["resueltas"] ?? 0)
        ];
    }
    
    public function obtenerUltimas(int $limite = 10): array
    {
    $sql = "SELECT
                u.cedula,
                i.descripcion,
                i.estado,
                i.fecha
            FROM incidencias i
            LEFT JOIN empleados e ON i.empleado_id = e.id
            LEFT JOIN usuarios u ON e.usuario_id = u.id
            ORDER BY i.fecha DESC
            LIMIT :limite";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorLaboratorio(): array
    {
        $sql = "SELECT laboratorio, COUNT(*) AS cantidad
                FROM incidencias
                GROUP BY laboratorio
                ORDER BY cantidad DESC";

        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorTipo(): array
    {
        $sql = "SELECT tipo, COUNT(*) AS cantidad
                FROM incidencias
                GROUP BY tipo
                ORDER BY cantidad DESC";

        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
