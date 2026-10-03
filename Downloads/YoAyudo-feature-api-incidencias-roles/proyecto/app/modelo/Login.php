<?php

require_once "AccesoDatosUsuario.php";

/**
 * Clase encargada de realizar la autenticación de usuarios.
 * Utiliza AccesoDatosUsuario para recuperar la información del usuario
 * y verifica su estado, contraseña y roles habilitados.
 */
class Login
{
    /**
     * Objeto encargado de acceder a los datos de los usuarios.
     *
     * @var AccesoDatosUsuario
     */
    private $accesoDatos;

    /**
     * Construye el objeto Login e inicializa el acceso a datos.
     */
    public function __construct()
    {
        $this->accesoDatos = new AccesoDatosUsuario();
    }

    /**
     * Autentica un usuario por su cédula y contraseña.
     * Comprueba que el usuario exista, se encuentre activo, que la
     * contraseña coincida con el hash almacenado y que tenga al menos
     * un rol asignado.
     *
     * @param string $cedula Cédula del usuario.
     * @param string $contrasena Contraseña ingresada por el usuario.
     * @return array Resultado de la autenticación. Contiene "ok" y,
     * en caso de que sea exitosa, los datos del usuario.
     */
    public function autenticar($cedula, $contrasena)
    {
        $usuario = $this->accesoDatos->buscarPorCedula($cedula);

        if ($usuario === null) {
            return [
                "ok" => false,
                "error" => "credenciales"
            ];
        }

        if ($usuario["estado"] != "activo") {
            return [
                "ok" => false,
                "error" => "inactivo"
            ];
        }

        if (!password_verify($contrasena, $usuario["contrasena"])) {
            return [
                "ok" => false,
                "error" => "credenciales"
            ];
        }

        if (count($usuario["roles"]) == 0) {
            return [
                "ok" => false,
                "error" => "sin_roles"
            ];
        }

        return [
            "ok" => true,
            "usuario" => $usuario
        ];
    }
}