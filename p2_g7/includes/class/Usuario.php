<?php


class Usuario{
    private $nombreUsuario;
    private $nombre;
    private $password;
    private $rol;

    const ADMIN_ROLE = 1;
    const USER_ROLE = 2;
    
    private function __construct($nombreUsuario, $nombre, $password, $rol = self::USER_ROLE){
        $this->nombreUsuario = $nombreUsuario;
        $this->nombre = $nombre;
        $this->password = $password;
        $this->rol = $rol;
    }

    private function getNombreUsuario(){
        return $this->nombreUsuario;
    }

    private function getNombre(){
        return $this->nombre;
    }

    private function getPassword(){
        return $this->password;
    }

    private function getRol(){
        return $this->rol;
    }
    
    private function setPassword($password){
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    private function setRol($rol){
        if ($rol === self::ADMIN_ROLE || $rol === self::USER_ROLE) {
            $this->rol = $rol;
        }
    }

    private function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function buscaUsuario($nombreUsuario){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("SELECT * FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        if ($rs) {
            $fila = $rs->fetch_assoc();
            $user = new Usuario($fila['nombreUsuario'], $fila['nombre'], $fila['password'], $fila['rol']);
            $rs->free();
            Aplicacion::getInstance()->shutdown();
            return $user;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        Aplicacion::getInstance()->shutdown();
        return $result;
    }

    public function buscaPassword($password){
        return password_verify($password, $this->password);
    }

    public static function login($nombreUsuario, $password){
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->buscaPassword($password)) {
            return $usuario;
        }
        return false;
    }

    public static function crea($nombreUsuario, $nombre, $password, $rol){
        $check = self::buscaUsuario($nombreUsuario);
        if ($check) {
            return $check;
        }
        $user = new Usuario($nombreUsuario, $nombre, null, $rol);
        $user->setPassword($password);
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO Usuarios (nombreUsuario, nombre, password, rol) VALUES ('%s', '%s', '%s', %d)", $conn->real_escape_string($user->getNombreUsuario()), $conn->real_escape_string($user->getNombre()), $conn->real_escape_string($user->getPassword()), $user->getRol());
        if ($conn->query($query)) {
            return $user;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        Aplicacion::getInstance()->shutdown();
    }
}