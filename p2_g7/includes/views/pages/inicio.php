<?php
include __DIR__ . '/../../config.php';

$tituloPagina = 'Inicio';

$contenidoPrincipal = <<<EOS
  <a href="./pedido.php?pagina=takeAway">Pedido para llevar</a>
  <a href="./pedido.php?pagina=eatIn">Para consumir en el local</a>
EOS;

require __DIR__ . '/plantilla.php';