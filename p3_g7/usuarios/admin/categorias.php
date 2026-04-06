<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioBusquedaCategoria;

$tituloPagina = 'Modificar categorías';

$formulario = new FormularioBusquedaCategoria();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Busqueda de categorías</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);