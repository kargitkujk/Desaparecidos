<?php

/**
 * Representa a un usuario del sistema.
 *
 * Contiene los datos necesarios para la autenticación y los roles
 * asociados al usuario.
 */
class Usuario
{
    /**
     * Cédula del usuario.
     *
     * @var string
     */
    private $cedula;

    /**
     * Nombre del usuario.
     *
     * @var string
     */
    private $nombre;

    /**
     * Hash de la contraseña del usuario.
     *
     * @var string
     */
    private $contrasena;

    /**
     * Estado actual del usuario.
     *
     * @var string
     */
    private $estado;

    /**
     * Roles asociados al usuario.
     *
     * @var array
     */
    private $roles = [];

    /**
     * Construye un objeto Usuario.
     *
     * @param string $cedula Cédula del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $contrasena Hash de la contraseña.
     * @param string $estado Estado del usuario.
     */
    public function __construct($cedula, $nombre, $contrasena, $estado)
    {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->estado = $estado;
    }

    /**
     * Obtiene la cédula del usuario.
     *
     * @return string Cédula del usuario.
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string Nombre del usuario.
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Obtiene el hash de la contraseña.
     *
     * @return string Hash de la contraseña.
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Obtiene el estado del usuario.
     *
     * @return string Estado del usuario.
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Obtiene los roles asociados al usuario.
     *
     * @return array Lista de roles del usuario.
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Establece los roles asociados al usuario.
     *
     * @param array $roles Lista de roles.
     * @return void
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}