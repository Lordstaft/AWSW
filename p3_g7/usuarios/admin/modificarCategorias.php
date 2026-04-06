<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioEditarCategoria;

$tituloPagina = 'Editar categorías';

$formulario = new FormularioEditarCategoria();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Editar categorías</h1>
    $formularioHTML
EOS;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);