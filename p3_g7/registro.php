<?php
require __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\usuarios\FormularioCrearUsuario;

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioCrearUsuario();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Crear Usuario</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';