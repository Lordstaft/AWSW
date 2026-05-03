<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$user = trim($_GET['user'] ?? '');
$user = filter_var($user, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id   = filter_var($_GET['id'] ?? null, FILTER_SANITIZE_NUMBER_INT);

$resultado = Usuario::buscaUsuario($user);

if ($resultado && $resultado->getId() != $id) {
    echo "existe";
} 

else {
    echo "disponible";
}