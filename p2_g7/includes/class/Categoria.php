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
	public static function buscaPorId($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM categorias WHERE id = %d",
            (int)$id
        );

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $categoria = $rs->fetch_assoc();
            $rs->free();
            return $categoria;
        }

        return null;
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
