<?php

class Aplicacion{
    private static $instancia = null;
    private $bdDatosConexion = null;
    private $inicializada = false;
    private $conn;

    protected function __construct(){

    }

    public static function getInstance() {
		if (!self::$instancia instanceof self) {
			self::$instancia = new static();
		}
		return self::$instancia;
	}

    public function init($bdDatosConexion){
        if($this->inicializada === true){
            return;
        }
        $this->bdDatosConexion = $bdDatosConexion;
        $this->inicializada = true;
    }

    public function shutdown(){
        if ($this->inicializada === true && $this->conn !== null) {
            $this->conn->close();
        }
    }

    public function getConexionBd(){
        if($this->inicializada === false){
            echo "Error: La aplicación no ha sido inicializada.";
            return null;
        }
        $this->conn = new mysqli($this->bdDatosConexion['host'], $this->bdDatosConexion['usuario'], $this->bdDatosConexion['password'], $this->bdDatosConexion['nombre']);

        if ($this->conn->connect_error) {
            die("Error de conexión a la base de datos: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}