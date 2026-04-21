<?php

require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\FormularioLogin;

$formLogin = new FormularioLogin();
$formLogin = $formLogin->gestiona();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $app->redirige($app->resuelve('/inicio.php'));
}

$tituloPagina = 'Login';
$contenidoPrincipal = <<<EOF
    <h1>Acceso al sistema</h1>
    $formLogin
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Login'];
$app->generaVista('/plantillas/plantilla.php', $params);