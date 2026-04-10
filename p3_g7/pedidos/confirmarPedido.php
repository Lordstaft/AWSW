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

/* Crear pedido */
$pedidoId = Pedido::crearPedido($usuarioObj->getId(), $tipo, $carrito);

/* Vaciar carrito */
$_SESSION['carrito'] = [];

/* Redirigir */
header('Location: '.$app->resuelve('/pedidos/pedidoConfirmado.php').'?id='.$pedidoId);
exit();