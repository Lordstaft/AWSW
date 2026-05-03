<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$user = $_GET['user'] ?? '';

$resultado = Usuario::buscaUsuario($user);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}