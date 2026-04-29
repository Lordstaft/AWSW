<?php
require __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioRealizarPedido;

$tituloPagina = 'Realizar pedido';

$formulario = new FormularioRealizarPedido();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);