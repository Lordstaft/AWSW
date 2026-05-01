<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioRealizarPedido;

$tituloPagina = 'Realizar Pedido';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioRealizarPedido();
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