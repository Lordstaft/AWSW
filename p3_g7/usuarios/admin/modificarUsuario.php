<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\usuarios\FormularioEditarUsuario;

$tituloPagina = 'Editar usuario';

$formulario = new FormularioEditarUsuario();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h2>Editar usuario</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);