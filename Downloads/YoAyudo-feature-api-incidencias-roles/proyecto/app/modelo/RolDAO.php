<?php

require_once __DIR__ . "/ConectorPDO.php";

/**
 * DAO que centraliza todas las operaciones de persistencia
 * relacionadas con los roles.
 */
class RolDAO
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = ConectorPDO::conectar();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT id, nombre
                FROM roles
                ORDER BY nombre";

        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT id, nombre
                FROM roles
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);
        $rol = $stmt->fetch(PDO::FETCH_ASSOC);

        return $rol ?: null;
    }

    public function buscarPorNombre(string $nombre): ?array
    {
        $sql = "SELECT id, nombre
                FROM roles
                WHERE nombre = :nombre
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":nombre" => $nombre]);
        $rol = $stmt->fetch(PDO::FETCH_ASSOC);

        return $rol ?: null;
    }

    public function registrar(string $nombre): bool
    {
        $sql = "INSERT INTO roles (nombre)
                VALUES (:nombre)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([":nombre" => $nombre]);
    }

    public function actualizar(int $id, string $nombre): bool
    {
        $sql = "UPDATE roles
                SET nombre = :nombre
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":nombre" => $nombre,
            ":id" => $id
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM roles WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->rowCount() > 0;
    }
}
