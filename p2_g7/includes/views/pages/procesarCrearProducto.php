<?php
require __DIR__ . '/../../config.php';

if (!isset($_POST['crearProducto'])) {
    header('Location: crearProducto.php');
    exit();
}

$nombreProd = trim($_POST['nombreProd'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria_id = trim($_POST['categoria_id'] ?? '');
$precio = trim($_POST['precio'] ?? '');
$iva = trim($_POST['iva'] ?? '');
$disponible = trim($_POST['disponible'] ?? '1');
$ofertado = trim($_POST['ofertado'] ?? '1');

if (
    $nombreProd !== '' &&
    $descripcion !== '' &&
    $categoria_id !== '' &&
    $precio !== '' &&
    $iva !== ''
) {
    Producto::crea($nombreProd, $descripcion, $categoria_id, $precio, $iva, $disponible, $ofertado);
    header('Location: productos.php');
    exit();
}

echo "Error: faltan campos obligatorios.";