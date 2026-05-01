<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioBusquedaProductos;

$tituloPagina = 'Búsqueda de Productos';

if (isset($_SESSION["esGerente"])) {
    $formulario = new FormularioBusquedaProductos();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Búsqueda de productos</h2>
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