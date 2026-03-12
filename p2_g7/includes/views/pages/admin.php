<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../class/forms/FormularioBusquedaUsuarios.php';

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioBusquedaUsuarios();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Buscar Usuario</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';