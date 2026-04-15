<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\ofertas\FormularioCrearOferta;

$tituloPagina = 'Crear Oferta';

$formulario = new FormularioCrearOferta();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
<h1>Crear oferta</h1>
$formularioHTML
EOS;

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);