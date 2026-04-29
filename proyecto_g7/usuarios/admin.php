<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioBusquedaUsuarios;

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioBusquedaUsuarios();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h2>Buscar Usuario</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);