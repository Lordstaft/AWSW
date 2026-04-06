<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Inicio';

$contenidoPrincipal = <<<EOS
  <a href="./pedido.php?pagina=takeAway">Pedido para llevar</a>
  <a href="./pedido.php?pagina=eatIn">Para consumir en el local</a>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Inicio'];
$app->generaVista('/plantillas/plantilla.php', $params);