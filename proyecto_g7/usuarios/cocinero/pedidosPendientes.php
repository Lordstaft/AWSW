<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioPedidosPendientes;

$tituloPagina = 'Pedidos Pendientes';

if (isset($_SESSION["esCocinero"])) {
    $formulario = new FormularioPedidosPendientes();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Pedidos pendientes</h2>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como cocinero para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);