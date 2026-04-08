<?php
namespace es\ucm\fdi\aw\pedidos;

enum EstadoPedido: string{
    case CANCELADO = 'cancelado';
    case RECIBIDO =  'enviado';
    case PENDIENTE = 'pendiente';
    case EN_PREPARACION = 'preparando';
    case EN_COCINA =  'cocinando';
    case ENTREGADO = 'entregado';
    case LISTO = 'listo';
}