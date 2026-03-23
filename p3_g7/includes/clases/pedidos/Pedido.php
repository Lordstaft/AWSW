<?php
namespace es\ucm\fdi\aw\pedidos;

class Pedido {

    public static function crearPedido($usuarioId, $tipo, $carrito) {

    $conn = Aplicacion::getInstance()->getConexionBd();

    $total = 0;

    foreach ($carrito as $id => $cantidad) {

        $producto = Producto::buscaPorId($id);

        if ($producto) {
            $total += $producto['precio'] * $cantidad;
        }
    }

    /* numero de pedido del día */

    $query = "SELECT COUNT(*) as total 
              FROM pedidos 
              WHERE DATE(fechaPedido) = CURDATE()";

    $res = $conn->query($query);
    $fila = $res->fetch_assoc();

    $numPedido = $fila['total'] + 1;

    $query = sprintf(
        "INSERT INTO pedidos(usuario_id, tipo, total, numPedido)
         VALUES (%d, '%s', %.2f, %d)",
        $usuarioId,
        $conn->real_escape_string($tipo),
        $total,
        $numPedido
    );

    $conn->query($query);

    $pedidoId = $conn->insert_id;

    foreach ($carrito as $id => $cantidad) {

        $producto = Producto::buscaPorId($id);

        if ($producto) {

            $query = sprintf(
                "INSERT INTO pedido_productos
                (pedido_id, producto_id, cantidad, precioUnitario, ivaAplicado)
                VALUES (%d,%d,%d,%.2f,'%s')",
                $pedidoId,
                $id,
                $cantidad,
                $producto['precio'],
                $producto['iva']
            );

            $conn->query($query);
        }
    }

    return $pedidoId;
}

}
