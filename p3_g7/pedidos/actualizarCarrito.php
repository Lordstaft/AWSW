<?php
require __DIR__ . '/../../config.php';

foreach ($_POST['cantidad'] as $id => $cantidad) {
    $_SESSION['carrito'][$id] = (int)$cantidad;
}

header("Location: carrito.php");