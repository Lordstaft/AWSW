<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioBusquedaProductos;

$tituloPagina = 'Busqueda de productos';

$formulario = new FormularioBusquedaProductos();
$formularioHTML = $formulario->gestiona();

$resultados = $_SESSION['resultadosBusqueda'];

$contenidoPrincipal = <<<EOS
    <h1>Busqueda de productos</h1>
    $resultados
EOS;

unset($_SESSION['resultadosBusqueda']);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);