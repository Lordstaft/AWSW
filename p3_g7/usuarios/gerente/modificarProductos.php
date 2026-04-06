<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioEditarProducto;

$tituloPagina = 'Editar producto';

$formulario = new FormularioEditarProducto();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h1>Editar producto</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);