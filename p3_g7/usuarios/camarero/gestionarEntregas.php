<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioEntregaPedido;

$tituloPagina = 'Entrega de Pedidos';

$formulario = new FormularioEntregaPedido();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h1>Entrega de Pedidos</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);