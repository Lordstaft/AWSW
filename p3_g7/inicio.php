<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Inicio';

$urlLlevar = $app->resuelve('/pedidos/realizarPedido.php?pedido=llevar');
$urlLocal = $app->resuelve('/pedidos/realizarPedido.php?pedido=local');

$contenidoPrincipal = <<<EOS
    <a href="$urlLlevar">
        Pedido para llevar
    </a>
    
    <a href="$urlLocal">
        Consumir en local
    </a>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);