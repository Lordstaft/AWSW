<?php
require __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\forms\FormularioBusquedaUsuarios;

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioBusquedaUsuarios();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Buscar Usuario</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';