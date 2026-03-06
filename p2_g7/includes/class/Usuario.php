<?php

class Usuario {

    private $id;
    private $nombreUsuario;
    private $email;
    private $nombre;
    private $apellidos;
    private $password;
    private $rol;
    private $avatar;
    private $fechaRegistro;

    const ADMIN_ROLE = 1;
    const USER_ROLE  = 2;

    public function __construct($id, $nombreUsuario, $email, $nombre, $apellidos, $password, $rol = self::USER_ROLE, $avatar = null, $fechaRegistro = null) {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->password = $password;
        $this->rol = $rol;
        $this->avatar = $avatar;
        $this->fechaRegistro = $fechaRegistro;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRol() {
        return $this->rol;
    }

    public function getId() {
        return $this->id;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, BCRYPT);
    }

    public function setRol($rol) {
        if ($rol === self::ADMIN_ROLE || $rol === self::USER_ROLE) {
            $this->rol = $rol;
        }
    }

    private function hashPassword($password) {
        return password_hash($password, BCRYPT);
    }

    public function buscaPassword($password) {
        return password_verify($password, $this->password);
    }

    public static function buscaUsuario($nombreUsuario) {

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $usuario = new Usuario(
                $fila['id'],
                $fila['nombreUsuario'],
                $fila['email'] ?? null,
                $fila['nombre'] ?? null,
                $fila['apellidos'] ?? null,
                $fila['contraseña'],
                $fila['rol'] ?? self::USER_ROLE,
                $fila['avatar'] ?? null,
                $fila['fechaRegistro'] ?? null
            );
            $rs->free();
            return $usuario;
        }

        return null;
    }

    public static function login($nombreUsuario, $password) {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->buscaPassword($password)) {
            return $usuario;
        }

        return false;
    }

    public static function crea($nombreUsuario, $nombre, $password, $rol = 'cliente', $email = '', $apellidos = '') {

    $check = self::buscaUsuario($nombreUsuario);
    if ($check) {
        return false;
    }

    $conn = Aplicacion::getInstance()->getConexionBd();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $query = sprintf(
        "INSERT INTO usuarios (nombreUsuario, email, nombre, apellidos, contraseña, rol)
         VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
        $conn->real_escape_string($nombreUsuario),
        $conn->real_escape_string($email),
        $conn->real_escape_string($nombre),
        $conn->real_escape_string($apellidos),
        $conn->real_escape_string($passwordHash),
        $conn->real_escape_string($rol)
    );

    if ($conn->query($query)) {
        $id = $conn->insert_id;
        return new Usuario(
            $id,
            $nombreUsuario,
            $email,
            $nombre,
            $apellidos,
            $passwordHash,
            $rol
        );
    }

    error_log("Error BD ({$conn->errno}): {$conn->error}");
    return false;
    }
}
