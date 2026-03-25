<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\productos\Producto;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    Producto::retirar($id);
}

header('Location: '.$app->resuelve('/productos/productos.php'));
exit();