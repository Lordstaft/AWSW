<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$user = $_GET['user'] ?? '';
$id   = $_GET['id'] ?? null;

$resultado = Usuario::buscaUsuario($user);

if ($resultado && $resultado->getId() != $id) {
    echo "existe";
} 

else {
    echo "disponible";
}