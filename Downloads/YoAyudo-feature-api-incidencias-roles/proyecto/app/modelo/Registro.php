<?php

require_once "ConectorPDO.php";

/**
 * Clase encargada de registrar nuevos usuarios en el sistema.
 * Valida los datos recibidos, comprueba que la cédula y el email
 * no estén repetidos, encripta la contraseña y guarda al usuariojunto con el rol asignado.
 */
class Registro
{
    /**
     * Conexión PDO usada para acceder a la base de datos.
     *
     * @var PDO
     */
    private $conexion;

    /**
     * Construye el objeto Registro e inicializa la conexión.
     */
    public function __construct()
    {
        $this->conexion = ConectorPDO::conectar();
    }

    /**
     * Valida los datos recibidos desde el formulario de registro.
     *
     * @param string $cedula cédula del usuario.
     * @param string $nombre nombre del usuario.
     * @param string $apellido apellido del usuario.
     * @param string $email correo electrónico del usuario.
     * @param string $contrasena contraseña ingresada.
     * @param string $confirmacion confirmación de la contraseña.
     * @param int $rolId ID del rol seleccionado.
     * @return array Lista de errores encontrados. Vacía si no hay errores.
     */
    public function validar($cedula, $nombre, $apellido, $email, $contrasena, $confirmacion, $rolId)
    {
        $errores = [];

        if (!preg_match("/^[0-9]{7,8}$/", $cedula)) {
            $errores[] = "La cédula debe tener entre 7 y 8 números.";
        }

        if (strlen($nombre) < 2) {
            $errores[] = "El nombre debe tener al menos 2 caracteres.";
        }

        if (strlen($apellido) < 2) {
            $errores[] = "El apellido debe tener al menos 2 caracteres.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no tiene un formato válido.";
        }

        if (strlen($contrasena) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres.";
        }

        if ($contrasena !== $confirmacion) {
            $errores[] = "Las contraseñas no coinciden.";
        }

        if (empty($rolId)) {
            $errores[] = "Debe seleccionar un rol.";
        }

        if (empty($errores) && $this->existeCedula($cedula)) {
            $errores[] = "Ya existe un usuario registrado con esa cédula.";
        }

        if (empty($errores) && $this->existeEmail($email)) {
            $errores[] = "Ya existe un usuario registrado con ese email.";
        }

        return $errores;
    }

    /**
     * Comprueba si ya existe un usuario con la cédula indicada.
     *
     * @param string $cedula Cédula a comprobar.
     * @return bool True si la cédula ya está registrada.
     */
    public function existeCedula($cedula)
    {
        $sql = "SELECT id FROM usuarios WHERE cedula = :cedula LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":cedula", $cedula);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Comprueba si ya existe un usuario con el email indicado.
     *
     * @param string $email email a comprobar.
     * @return bool True si el email ya está registrado.
     */
    public function existeEmail($email)
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Registra un nuevo usuario junto con su rol asignado.
     * Encripta la contraseña con password_hash antes de guardarla.
     * La inserción del usuario y la asignación del rol se ejecutan
     * dentro de una transacción para mantener la consistencia de los datos.
     *
     * @param string $cedula cédula del usuario.
     * @param string $nombre nombre del usuario.
     * @param string $apellido apellido del usuario.
     * @param string $email correo electrónico del usuario.
     * @param string $contrasena contraseña en texto plano.
     * @param int $rolId ID del rol asignado al usuario.
     * @return array Resultado de la operación. Contiene "ok" y
     * en caso de error, un mensaje descriptivo.
     */
    public function registrar($cedula, $nombre, $apellido, $email, $contrasena, $rolId)
    {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        try {
            $this->conexion->beginTransaction();

            $sql = "INSERT INTO usuarios
                    (cedula, nombre, apellido, email, contrasena, estado)
                    VALUES
                    (:cedula, :nombre, :apellido, :email, :contrasena, 'activo')";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                ":cedula" => $cedula,
                ":nombre" => $nombre,
                ":apellido" => $apellido,
                ":email" => $email,
                ":contrasena" => $hash
            ]);

            $usuarioId = $this->conexion->lastInsertId();

            $sqlRol = "INSERT INTO usuario_rol (usuario_id, rol_id)
                       VALUES (:usuario_id, :rol_id)";

            $stmtRol = $this->conexion->prepare($sqlRol);

            $stmtRol->execute([
                ":usuario_id" => $usuarioId,
                ":rol_id" => $rolId
            ]);

            $sqlEmpleado = "INSERT INTO empleados (usuario_id)
                            VALUES (:usuario_id)";

            $stmtEmpleado = $this->conexion->prepare($sqlEmpleado);

            $stmtEmpleado->execute([
                ":usuario_id" => $usuarioId
            ]);

            $this->conexion->commit();

            return [
                "ok" => true,
                "usuario_id" => $usuarioId
            ];

        } catch (PDOException $e) {

            $this->conexion->rollBack();

            return [
                "ok" => false,
                "error" => "Ocurrió un error al registrar el usuario."
            ];
        }
    }
}