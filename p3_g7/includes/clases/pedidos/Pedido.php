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

// Las siguientes funciones corresponden a la funcionalidad 3
 /* Pedidos que ve el gerente */

    public static function getPedidosPendientes() {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "
            SELECT 
                p.id,
                u.nombre AS usuario,
                p.estado,
                c.nombre AS cocinero
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            LEFT JOIN usuarios c ON p.cocinero_id = c.id
            WHERE p.estado != 'entregado'
            ORDER BY p.id DESC
        ";

        $res = $conn->query($query);

        $pedidos = [];

        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $pedidos[] = $fila;
            }
        }

        return $pedidos;
    }

    /* Pedidos que ve la cocina */

    public static function getPedidosCocina() {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "
            SELECT id, estado, usuario_id
            FROM pedidos
            WHERE estado IN ('recibido', 'en_preparacion', 'cocinando')
            ORDER BY id ASC
        ";

        $res = $conn->query($query);

        $pedidos = [];

        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $pedidos[] = $fila;
            }
        }

        return $pedidos;
    }

    /* Un cocinero se queda el pedido */

    public static function asignarCocinero($pedidoId, $cocineroId) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedidos
             SET cocinero_id = %d,
                 estado = 'cocinando'
             WHERE id = %d",
            $cocineroId,
            $pedidoId
        );

        return $conn->query($query);
    }

    /* Marcar producto preparado */

    public static function marcarProductoPreparado($pedidoProductoId) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedido_productos
             SET preparado = 1
             WHERE id = %d",
            $pedidoProductoId
        );

        return $conn->query($query);
    }

    /* Finalizar preparación */

    public static function finalizarPedido($pedidoId) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedidos
             SET estado = 'preparado'
             WHERE id = %d",
            $pedidoId
        );

        return $conn->query($query);
    }

}
