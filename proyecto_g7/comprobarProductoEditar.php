<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\productos\Producto;

$producto = $_GET['producto'] ?? '';
$id   = $_GET['id'] ?? null;

$resultado = Producto::validarProducto($producto);

if ($resultado && $resultado->getId() !== $id) {
    echo "existe";
} 

else {
    echo "disponible";
}