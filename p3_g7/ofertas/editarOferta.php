<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\ofertas\FormularioEditarOferta;

$idOferta = $_GET['id'] ?? 0;
$idOferta = (int)$idOferta;

$tituloPagina = 'Editar Oferta';

$formulario = new FormularioEditarOferta($idOferta);
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
<h1>Editar oferta</h1>
$formularioHTML
EOS;

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);