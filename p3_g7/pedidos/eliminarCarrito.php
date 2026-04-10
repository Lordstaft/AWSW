<?php
require __DIR__ . '/../../config.php';

$id = $_GET['id'];

unset($_SESSION['carrito'][$id]);

header("Location: carrito.php");