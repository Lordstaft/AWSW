<?php
require_once __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\ofertas\FormularioCrearOferta;

$tituloPagina = 'Crear Oferta';

if (isset($_SESSION["esGerente"])) {
    $form = new FormularioCrearOferta();
    $htmlForm = $form->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Crear oferta</h1>
        $htmlForm
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como gerente para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'BistroFDI'];
$app->generaVista('/plantillas/plantilla.php', $params);