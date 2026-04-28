<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioBusquedaProductos;

$tituloPagina = 'Busqueda de productos';

$formulario = new FormularioBusquedaProductos();
$formularioHTML = $formulario->gestiona();

$resultados = $_SESSION['resultadosBusqueda'];

$contenidoPrincipal = <<<EOS
    <h2>Busqueda de productos</h2>
    $resultados
EOS;

unset($_SESSION['resultadosBusqueda']);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);