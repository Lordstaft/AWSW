<?php
require __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\Pedido;
use es\ucm\fdi\aw\Usuario;

if(!isset($_SESSION['login'])){
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['nombreUsuario'];

$usuarioObj = Usuario::buscaUsuario($usuario);

$carrito = $_SESSION['carrito'] ?? [];

if(empty($carrito)){
    header("Location: carrito.php");
    exit();
}

$tipo = $_SESSION['pedido'];

$pedidoId = Pedido::crearPedido($usuarioObj->getId(), $tipo, $carrito);

$_SESSION['carrito'] = [];

header("Location: pedidoConfirmado.php?id=$pedidoId");
exit();