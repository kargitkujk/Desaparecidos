<?php

require_once __DIR__ . "/ConectorPDO.php";

class UsuarioDAO
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = ConectorPDO::conectar();
    }

    public function buscarPorCedula(string $cedula): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":cedula" => $cedula]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return null;
        }

        $sqlRoles = "SELECT r.nombre
                     FROM roles r
                     INNER JOIN usuario_rol ur ON r.id = ur.rol_id
                     WHERE ur.usuario_id = :usuario_id";
        $stmtRoles = $this->conexion->prepare($sqlRoles);
        $stmtRoles->execute([":usuario_id" => $usuario["id"]]);

        $roles = [];
        while ($rol = $stmtRoles->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $rol["nombre"];
        }
        $usuario["roles"] = $roles;

        return $usuario;
    }

    public function registrarUsuario(
    string $cedula,
    string $nombre,
    string $apellido,
    string $email,
    string $contrasena,
    int $rolId
): array {
    try {
        // Hashear la contraseña
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar usuario
        $sqlUsuario = "INSERT INTO usuarios (cedula, nombre, apellido, email, contrasena, estado)
                       VALUES (:cedula, :nombre, :apellido, :email, :contrasena, 'activo')";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);
        $stmtUsuario->execute([
            ":cedula"     => $cedula,
            ":nombre"     => $nombre,
            ":apellido"   => $apellido,
            ":email"      => $email,
            ":contrasena" => $hash
        ]);

        $usuarioId = (int) $this->conexion->lastInsertId();

        // Asignar rol
        $sqlRol = "INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)";
        $stmtRol = $this->conexion->prepare($sqlRol);
        $stmtRol->execute([
            ":usuario_id" => $usuarioId,
            ":rol_id"     => $rolId
        ]);

        // Registrar como empleado
        $sqlEmpleado = "INSERT INTO empleados (usuario_id) VALUES (:usuario_id)";
        $stmtEmpleado = $this->conexion->prepare($sqlEmpleado);
        $stmtEmpleado->execute([":usuario_id" => $usuarioId]);

        return ["ok" => true];
    } catch (PDOException $e) {
        return ["ok" => false, "error" => $e->getMessage()];
    }
}

    public function actualizarUsuarioYRol(int $id, string $nombre, string $apellido, string $estado, int $rolId): bool
    {
        $sql = "UPDATE usuarios
                SET nombre = :nombre, apellido = :apellido, estado = :estado
                WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $resultado = $stmt->execute([
            ":nombre" => $nombre,
            ":apellido" => $apellido,
            ":estado" => $estado,
            ":id" => $id
        ]);

        if (!$resultado) {
            return false;
        }

        $this->conexion->prepare("DELETE FROM usuario_rol WHERE usuario_id = :id")
            ->execute([":id" => $id]);

        $stmtInsertar = $this->conexion->prepare(
            "INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)"
        );
        return $stmtInsertar->execute([
            ":usuario_id" => $id,
            ":rol_id" => $rolId
        ]);
    }

    public function obtenerEmpleadoIdPorUsuario(int $usuarioId): ?int
    {
        $sql = "SELECT id FROM empleados WHERE usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":usuario_id" => $usuarioId]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $empleado ? (int)$empleado["id"] : null;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT
                    u.id,
                    u.cedula,
                    u.nombre,
                    u.apellido,
                    u.estado,
                    GROUP_CONCAT(r.nombre SEPARATOR ', ') AS roles
                FROM usuarios u
                LEFT JOIN usuario_rol ur ON u.id = ur.usuario_id
                LEFT JOIN roles r ON ur.rol_id = r.id
                GROUP BY u.id
                ORDER BY u.nombre ASC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    public function obtenerRolIdDeUsuario(int $id): int|false
    {
        $sql = "SELECT rol_id FROM usuario_rol WHERE usuario_id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetchColumn();
    }

    public function alternarEstado(int $id): bool
    {
        $usuario = $this->buscarPorId($id);
        if (!$usuario) {
            return false;
        }
        $nuevoEstado = $usuario["estado"] === "activo" ? "inactivo" : "activo";
        $sql = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([":estado" => $nuevoEstado, ":id" => $id]);
    }
}