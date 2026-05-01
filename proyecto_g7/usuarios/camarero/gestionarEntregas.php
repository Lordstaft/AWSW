<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioEntregaPedido;

$tituloPagina = 'Entrega de Pedidos';

if (isset($_SESSION["esCamarero"])) {
    $formulario = new FormularioEntregaPedido();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Entrega de pedidos</h1>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como camarero para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);