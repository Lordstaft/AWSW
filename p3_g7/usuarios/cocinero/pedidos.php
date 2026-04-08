<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioPedidosEnCocina;

$tituloPagina = 'Pedidos sin cocinar';

$formulario = new FormularioPedidosEnCocina();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h1>Resultados de la búsqueda</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);

