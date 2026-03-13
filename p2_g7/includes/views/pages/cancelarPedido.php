<?php
require __DIR__ . '/../../config.php';

$_SESSION['carrito'] = [];

header("Location: inicio.php");
exit();