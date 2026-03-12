<?php

class Categoria
{
    public static function listar()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = "SELECT id, nombre, descripcion, imgCategoriaProd
                FROM categorias
                ORDER BY nombre";

        $rs = $conn->query($sql);

        $categorias = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $categorias[] = $fila;
            }
            $rs->free();
        }

        return $categorias;
    }
    
    public static function crea($nombre, $descripcion, $imgCategoriaProd = '')
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
	public static function buscaCategoriaPorId($id)
	{
	    $app = Aplicacion::getInstance();
	    $conn = $app->getConexionBd();
	
	    $query = "SELECT * FROM categorias WHERE id = ?";
	    $stmt = $conn->prepare($query);
	    $stmt->bind_param("i", $id);
	    $stmt->execute();
	
	    $result = $stmt->get_result();
	    return $result->fetch_assoc();
	}
	
	public static function actualizaCategoria($id, $nombre, $descripcion, $imgCategoriaProd)
	{
	    $app = Aplicacion::getInstance();
	    $conn = $app->getConexionBd();
	
	    $query = "UPDATE categorias 
	              SET nombre = ?, descripcion = ?, imgCategoriaProd = ?
	              WHERE id = ?";
	    $stmt = $conn->prepare($query);
	    $stmt->bind_param("sssi", $nombre, $descripcion, $imgCategoriaProd, $id);
	
	    return $stmt->execute();
	}

}

