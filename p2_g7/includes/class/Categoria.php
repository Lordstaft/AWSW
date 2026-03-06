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
}