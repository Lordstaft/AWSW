<?php
require_once __DIR__.'/includes/config.php';

use \es\ucm\fdi\aw\usuarios\FormularioLogout;

if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    $app->redirige('index.php');
}

$formLogout = new FormularioLogout();
$formLogout->gestiona();