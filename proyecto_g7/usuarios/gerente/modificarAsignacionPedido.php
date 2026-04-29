<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioEditarAsignacion;

$tituloPagina = 'Modificar asignación de pedido';

$formulario = new FormularioEditarAsignacion();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h2>Modificar asignación de pedido</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);