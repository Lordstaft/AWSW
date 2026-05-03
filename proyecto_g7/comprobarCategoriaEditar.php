<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\productos\Categoria;

$categoria = $_GET['categoria'] ?? '';
$id   = $_GET['id'] ?? null;

$resultado = Categoria::validarCategoria($categoria);

if ($resultado && $resultado->getId() !== $id) {
    echo "existe";
} 

else {
    echo "disponible";
}