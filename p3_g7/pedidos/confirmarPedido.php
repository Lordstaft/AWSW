<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();

/* Comprobar login */
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

/* Usuario */
$usuario = $_SESSION['nombreUsuario'] ?? '';
$usuarioObj = Usuario::buscaUsuario($usuario);

/* Carrito */
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    header('Location: '.$app->resuelve('/pedidos/carrito.php'));
    exit();
}

/* Tipo pedido (POST desde pago) */
$tipo = $_POST['tipo'] ?? 'recogida';

/* Calcular subtotal */
$subtotalSinDescuento = 0;

foreach ($carrito as $idProducto => $cantidad) {
    $producto = Producto::buscaPorId($idProducto);

    $precio = (float) $producto->getPrecio();
    $iva = (int) $producto->getIva();

    $precioConIva = $precio + ($precio * $iva / 100);
    $subtotalSinDescuento += $precioConIva * $cantidad;
}

/* Calcular ofertas */
$idsOfertas = $_SESSION['ofertas_activadas'] ?? [];

$resultadoOfertas = Oferta::calcularOfertasAplicadas($carrito, $idsOfertas);

$descuentoAplicado = $resultadoOfertas['descuentoTotal'];
$total = $subtotalSinDescuento - $descuentoAplicado;

if ($total < 0) {
    $total = 0;
}

/* Crear pedido */
$pedidoId = Pedido::crearPedido(
    $usuarioObj->getId(),
    $tipo,
    'pendiente',
    $subtotalSinDescuento,
    $descuentoAplicado,
    $total
);

/* Guardar ofertas aplicadas */
if ($pedidoId) {
    foreach ($resultadoOfertas['ofertasAplicadas'] as $ofertaAplicada) {
        Pedido::insertarOfertaPedido(
            $pedidoId,
            $ofertaAplicada['oferta_id'],
            $ofertaAplicada['veces'],
            $ofertaAplicada['descuento']
        );
    }
}

/* Vaciar carrito */
$_SESSION['carrito'] = [];
$_SESSION['ofertas_activadas'] = [];

/* Redirigir */
header('Location: '.$app->resuelve('/pedidos/pedidoConfirmado.php').'?id='.$pedidoId);
exit();