<?php
require_once __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\ofertas\FormularioEditarOferta;

$tituloPagina = 'Editar Oferta';

if (isset($_SESSION["esGerente"])) {
    $idOferta = $_GET['id'] ?? 0;
    $idOferta = (int)$idOferta;

    $form = new FormularioEditarOferta($idOferta);
    $htmlForm = $form->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Editar oferta</h1>
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