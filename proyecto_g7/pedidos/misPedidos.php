<?php
require __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioMisPedidos;

$tituloPagina = 'Mis pedidos';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioMisPedidos();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        $formularioHTML
    EOS;
}

else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);