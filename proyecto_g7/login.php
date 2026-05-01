<?php
require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\aw\usuarios\FormularioLogin;

$tituloPagina = 'Login';

if (!isset($_SESSION["login"])) {
  $formLogin = new FormularioLogin();
  $formLogin = $formLogin->gestiona();

  $contenidoPrincipal=<<<EOF
      <h1>Acceso al sistema</h1>
      $formLogin
  EOF;
}
else {
  $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Ya hay una sesión iniciada.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Login'];
$app->generaVista('/plantillas/plantilla.php', $params);