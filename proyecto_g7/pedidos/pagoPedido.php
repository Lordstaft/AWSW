<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioPagar;

$tituloPagina = 'Realizar Pago';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioPagar();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);