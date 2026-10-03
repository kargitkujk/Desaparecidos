<?php
require_once __DIR__ . '/ConectorPDO.php';

class PrestamoDAO {

private PDO $conexion;

private function obtenerConfigDb($nombre, $porDefecto = null) {
    if (defined($nombre)) {
        return constant($nombre);
    }

    if (isset($_ENV[$nombre])) {
        return $_ENV[$nombre];
    }

    if (isset($_SERVER[$nombre])) {
        return $_SERVER[$nombre];
    }

    $valor = getenv($nombre);
    if ($valor !== false) {
        return $valor;
    }

    return $porDefecto;
}

public function __construct() {
    $host = $this->obtenerConfigDb('DB_HOST', 'localhost');
    $nombreBd = $this->obtenerConfigDb('DB_NAME', 'yoayudo');
    $usuario = $this->obtenerConfigDb('DB_USER', 'root');
    $password = $this->obtenerConfigDb('DB_PASS', '');

    $this->conexion = new PDO(
        "mysql:host={$host};dbname={$nombreBd};charset=utf8mb4",
        $usuario,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
}

public function crearPrestamo($id_usuario, $monto, $fecha) {
    $stmt = $this->conexion->prepare("INSERT INTO prestamos (id_usuario, monto, fecha) VALUES (:id_usuario, :monto, :fecha)");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':monto', $monto);
    $stmt->bindParam(':fecha', $fecha);
    return $stmt->execute();
}
public function obtenerHistorialPorUsuario($id_usuario) {
    $stmt = $this->conexion->prepare("SELECT * FROM prestamos WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function cerrarPrestamo($id_prestamo) {
    $stmt = $this->conexion->prepare("UPDATE prestamos SET estado = 'cerrado' WHERE id = :id_prestamo");
    $stmt->bindParam(':id_prestamo', $id_prestamo);
    return $stmt->execute();
}
}