<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioCrearCategoria;

$tituloPagina = 'Registro categoria';
$formulario = new FormularioCrearCategoria();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Crear categoria</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);