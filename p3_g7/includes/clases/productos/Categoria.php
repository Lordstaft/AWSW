<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Categoria
{
    use MagicProperties;

    private $id;
    private $nombre;
    private $descripcion;
    private $imgCategoriaProd;

    public function __construct($id, $nombre, $descripcion, $imgCategoriaProd)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->imgCategoriaProd = $imgCategoriaProd ?? '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getImgCategoriaProd()
    {
        return $this->imgCategoriaProd;
    }

    public static function listar()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = "SELECT id, nombre, descripcion, imgCategoriaProd FROM categorias ORDER BY nombre";

        $rs = $conn->query($sql);

        $categorias = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {

                $categorias[] = new Categoria(
                    $fila['id'],
                    $fila['nombre'],
                    $fila['descripcion'],
                    $fila['imgCategoriaProd']
                );
            }

            $rs->free();
        }

        return $categorias;
    }

    public static function buscaCategorias(){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = "SELECT * FROM categorias ORDER BY nombre";

        $rs = $conn->query($sql);

        $categorias = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {

                $categorias[] = new Categoria(
                    $fila['id'],
                    $fila['nombre'],
                    $fila['descripcion'],
                    $fila['imgCategoriaProd']
                );
            }

            $rs->free();
        }

        return $categorias;
    }

    public static function buscaPorNombre($nombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = sprintf("SELECT * FROM categorias WHERE nombre = '%s'",
            $conn->real_escape_string($nombre)
        );

        $rs = $conn->query($sql);

        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $categoria = new Categoria(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['imgCategoriaProd']
            );

            $rs->free();
            return $categoria;
        }

        return null;
    }
    
    public static function crea($nombre, $descripcion, $imgCategoriaProd)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO categorias (nombre, descripcion, imgCategoriaProd)
            VALUES ('%s', '%s', '%s')",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($imgCategoriaProd)
        );

        return $conn->query($query);
    }

    public static function buscaPorId($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM categorias WHERE id = %d",
            (int)$id
        );

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {

            $fila = $rs->fetch_assoc();

            $categoria = new Categoria(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['imgCategoriaProd']
            );

            $rs->free();

            return $categoria;
        }

        return null;
    }

    public static function borra($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "DELETE FROM categorias WHERE id = %d",
            (int)$id
        );

        return $conn->query($query);
    }

    public static function actualiza($id, $nombre, $descripcion, $imgCategoriaProd)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE categorias
             SET nombre = '%s',
                 descripcion = '%s',
                 imgCategoriaProd = '%s'
             WHERE id = %d",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($imgCategoriaProd),
            (int)$id
        );

        return $conn->query($query);
    }
}
