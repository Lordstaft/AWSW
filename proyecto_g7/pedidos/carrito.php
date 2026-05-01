<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioCarrito;

$tituloPagina = 'Carrito';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioCarrito();
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