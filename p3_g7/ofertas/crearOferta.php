<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\ofertas\FormularioCrearOferta;

$tituloPagina = 'Crear Oferta';

$form = new FormularioCrearOferta();
$htmlForm = $form->gestiona();

$contenidoPrincipal = <<<EOS
<h2>Crear oferta</h2>
$htmlForm
EOS;

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);