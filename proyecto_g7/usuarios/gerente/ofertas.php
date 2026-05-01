<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\ofertas\FormularioGestionarOfertas;

$tituloPagina = 'Gestión de Ofertas';

if (isset($_SESSION["esGerente"])) {
    $formulario = new FormularioGestionarOfertas();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Listado de ofertas</h2>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como gerente para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);