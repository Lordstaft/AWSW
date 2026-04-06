<?php
namespace es\ucm\fdi\aw\pedidos;

class EstadosPedido
{
    const CANCELADO = 'cancelado';
    const RECIBIDO =  'enviado';
    const PENDIENTE = 'pendiente';
    const EN_PREPARACION = 'preparando';
    const EN_COCINA =  'cocinando';
    const ENTREGADO = 'entregado';
}