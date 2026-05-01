<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioPedidosEnCocina;

$tituloPagina = 'Pedidos sin cocinar';

if (isset($_SESSION["esCocinero"])) {
    $formulario = new FormularioPedidosEnCocina();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Resultados de la búsqueda</h2>
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