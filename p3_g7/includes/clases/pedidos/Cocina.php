<?php
namespace es\ucm\fdi\aw\pedidos;

class Cocina
{
    public static function getPedidos()
    {
        return Pedido::getPedidosCocina();
    }

    public static function asignarPedido($pedidoId, $cocineroId)
    {
        return Pedido::asignarCocinero($pedidoId, $cocineroId);
    }

    public static function prepararProducto($pedidoProductoId)
    {
        return Pedido::marcarProductoPreparado($pedidoProductoId);
    }

    public static function finalizarPedido($pedidoId)
    {
        return Pedido::finalizarPedido($pedidoId);
    }

    public static function getLineasPedido($pedidoId)
    {
        return Pedido::getLineasPedido($pedidoId);
    }
}