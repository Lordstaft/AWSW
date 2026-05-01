<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioCrearProducto;

$tituloPagina = 'Crear Producto';

if (isset($_SESSION["esGerente"])) {
    $formulario = new FormularioCrearProducto();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Crear producto</h2>
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