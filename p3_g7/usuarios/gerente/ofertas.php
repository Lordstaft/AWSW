<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\ofertas\FormularioGestionarOfertas;

$tituloPagina = 'Gestion de ofertas';
$formulario = new FormularioGestionarOfertas();

$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h2>Listado de ofertas</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);