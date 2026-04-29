<?php
require __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioPagar;

$tituloPagina = 'Realizar pago';

$formulario = new FormularioPagar();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);