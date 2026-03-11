<?php

class Producto {

    public static function listar() {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $sql = "SELECT p.id, p.nombreProd, p.descripcion, p.precio, p.iva, p.disponible, p.ofertado,
                       c.nombre AS categoria
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.nombreProd";

        $rs = $conn->query($sql);

        $productos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $productos[] = $fila;
            }
            $rs->free();
        }

        return $productos;
    }
    
    public static function crea($nombreProd, $descripcion, $categoria_id, $precio, $iva, $disponible = 1, $ofertado = 1)
    {
    $conn = Aplicacion::getInstance()->getConexionBd();

    $query = sprintf(
        "INSERT INTO productos (nombreProd, descripcion, categoria_id, precio, iva, disponible, ofertado)
         VALUES ('%s', '%s', %d, %.2f, '%s', %d, %d)",
        $conn->real_escape_string($nombreProd),
        $conn->real_escape_string($descripcion),
        (int)$categoria_id,
        (float)$precio,
        $conn->real_escape_string($iva),
        (int)$disponible,
        (int)$ofertado
    );

    return $conn->query($query);
    }

}
