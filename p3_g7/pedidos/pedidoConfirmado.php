<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Usuario;

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

$usuario = $_SESSION['nombreUsuario'] ?? '';
$usuarioObj = Usuario::buscaUsuario($usuario);

$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    header('Location: '.$app->resuelve('/pedidos/carrito.php'));
    exit();
}

$tipo = $_SESSION['pedido'] ?? 'recogida';

$pedidoId = Pedido::crearPedido($usuarioObj->getId(), $tipo, $carrito);

$_SESSION['carrito'] = [];

header('Location: '.$app->resuelve('/pedidos/pedidoConfirmado.php').'?id='.$pedidoId);
exit();