<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$email = $_GET['email'] ?? '';

$resultado = Usuario::checkEmail($email);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}