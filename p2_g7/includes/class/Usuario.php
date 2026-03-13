<?php

require __DIR__ . '/Roles.php';

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

    public function __construct($id, $nombreUsuario, $email, $nombre, $apellidos, $password, $rol, $avatar, $fechaRegistro) {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->password = $password;
        $this->rol = $rol;
        $this->avatar = $avatar ?? "default.jpg";
        $this->fechaRegistro = $fechaRegistro ?? '';
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

    public function getEmail() {
        return $this->email;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }

    public function getId() {
        return $this->id;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, BCRYPT);
    }

    public function setRol($rol) {
            $this->rol = $rol;
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
                    $fila['email'],
                    $fila['nombre'],
                    $fila['apellidos'],
                    $fila['contraseña'],
                    $fila['rol'],
                    $fila['avatar'],
                    $fila['fechaRegistro']
            );
            $rs->free();
            return $usuario;
        }

        return null;
    }

    public static function buscaUsuarioId($id){
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE id = '%d'", (int)$id);
        $rs = $conn->query($query);
        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $usuario = new Usuario(
                    $fila['id'],
                    $fila['nombreUsuario'],
                    $fila['email'],
                    $fila['nombre'],
                    $fila['apellidos'],
                    null,
                    $fila['rol'],
                    $fila['avatar'],
                    $fila['fechaRegistro']
            );
            $rs->free();
            return $usuario;
        }

        return null;
    }

    public static function buscaRolUsuariosAdmin($rol) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        if($rol === 'Todos' || $rol === '') {
            $query = sprintf("SELECT * FROM usuarios WHERE rol != 'admin'");
        }
        else{
            $query = sprintf("SELECT * FROM usuarios WHERE rol = '%s'", $conn->real_escape_string($rol));
        }
        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $usuarios = [];
            while ($fila = $rs->fetch_assoc()) {
                $usuarios[] = new Usuario(
                    null,
                    $fila['nombreUsuario'],
                    $fila['email'],
                    $fila['nombre'],
                    $fila['apellidos'],
                    null,
                    $fila['rol'],
                    $fila['avatar'] ?? null,
                    $fila['fechaRegistro']
                );
            }
            $rs->free();
            return $usuarios;
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

    public static function creaUsuario($nombreUsuario, $nombre, $password, $rol, $email, $apellidos) {

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
            $usuario = self::buscaUsuario($nombreUsuario);
            return $usuario;
        }

        error_log("Error BD ({$conn->errno}): {$conn->error}");
        return false;
    }

    public static function eliminarUsuario($id){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("DELETE FROM usuarios WHERE id = '%d'", (int)$id);

        return $conn->query($query);
    }

    public static function editarUsuario($id, $nombreUsuario, $nombre, $apellidos, $email, $rol) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE usuarios SET nombreUsuario = '%s', email = '%s', nombre = '%s', apellidos = '%s', rol = '%s' WHERE id = '%d'",
            $conn->real_escape_string($nombreUsuario),
            $conn->real_escape_string($email),
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($apellidos),
            $conn->real_escape_string($rol),
            (int)$id
        );

        return $conn->query($query);
    }
}
