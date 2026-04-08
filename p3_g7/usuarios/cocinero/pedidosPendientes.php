<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioPedidosPendientes;

$tituloPagina = 'Pedidos Pendientes';
$formulario = new FormularioPedidosPendientes();

$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Pedidos Pendientes</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);

