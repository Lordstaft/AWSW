<?php
require __DIR__ . '/../../config.php';

$id = $_GET['id'] ?? null;
$cantidad = $_GET['cantidad'] ?? 1;

if(!$id){
    header("Location: pedido.php");
    exit();
}

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

if(!isset($_SESSION['carrito'][$id])){
    $_SESSION['carrito'][$id] = $cantidad;
}else{
    $_SESSION['carrito'][$id] += $cantidad;
}

header("Location: carrito.php");
exit();