<?php
require_once __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\ofertas\FormularioCrearOferta;

$tituloPagina = 'Crear Oferta';

if (isset($_SESSION["esGerente"])) {
    $form = new FormularioCrearOferta();
    $htmlForm = $form->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Crear oferta</h2>
        $htmlForm
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como gerente para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'BistroFDI'];
$app->generaVista('/plantillas/plantilla.php', $params);