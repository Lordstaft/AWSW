<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioBusquedaProductos;

$tituloPagina = 'Busqueda de productos';

$formulario = new FormularioBusquedaProductos();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Busqueda de productos</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);