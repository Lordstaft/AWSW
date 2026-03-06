<?php
require __DIR__ . '/../../config.php';

if (!isset($_POST['crearCategoria'])) {
    header('Location: crearCategoria.php');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$imgCategoriaProd = trim($_POST['imgCategoriaProd'] ?? '');

if ($nombre !== '' && $descripcion !== '') {
    Categoria::crea($nombre, $descripcion, $imgCategoriaProd);
    header('Location: categorias.php');
    exit();
}

echo "Error: faltan campos obligatorios.";