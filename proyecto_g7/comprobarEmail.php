<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\Usuario;

$email = trim($_GET['email'] ?? '');
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$resultado = Usuario::checkEmail($email);

if ($resultado) {
    echo "existe";
} 

else {
    echo "disponible";
}