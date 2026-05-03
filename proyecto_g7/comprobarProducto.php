<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\productos\Producto;

$producto = $_GET['producto'] ?? '';

$resultado = Producto::validarProducto($producto);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}