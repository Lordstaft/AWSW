<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioPedidosAsignados;

$tituloPagina = 'Gestion de pedidos';
$formulario = new FormularioPedidosAsignados();

$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h2>Gestion de Pedidos</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);