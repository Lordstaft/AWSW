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
}