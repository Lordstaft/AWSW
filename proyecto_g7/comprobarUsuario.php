<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$user = trim($_GET['user'] ?? '');
$user = filter_var($user, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$resultado = Usuario::buscaUsuario($user);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}