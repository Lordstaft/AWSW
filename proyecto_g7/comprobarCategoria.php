<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\productos\Categoria;

$categoria = $_GET['categoria'] ?? '';

$resultado = Categoria::validarCategoria($categoria);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}