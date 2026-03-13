<?php
require_once __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\Producto;

$id = $_GET['id'] ?? null;

if ($id) {
    Producto::retirarProducto((int)$id);
}

header('Location: index.php?pagina=listadoProductos');
exit();