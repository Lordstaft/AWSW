<?php
require __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioCrearUsuario;

$tituloPagina = 'Crear usuario';
$formulario = new FormularioCrearUsuario();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Crear Usuario</h1>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Registro'];
$app->generaVista('/plantillas/plantilla.php', $params);
