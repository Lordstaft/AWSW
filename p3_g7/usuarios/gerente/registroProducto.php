<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioCrearProducto;

$tituloPagina = 'Crear Producto';
$formulario = new FormularioCrearProducto();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Crear Producto</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);