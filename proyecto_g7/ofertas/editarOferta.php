<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\ofertas\FormularioEditarOferta;

$idOferta = $_GET['id'] ?? 0;
$idOferta = (int)$idOferta;

$tituloPagina = 'Editar Oferta';

$form = new FormularioEditarOferta($idOferta);
$htmlForm = $form->gestiona();

$contenidoPrincipal = <<<EOS
<h2>Editar oferta</h2>
$htmlForm
EOS;

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);