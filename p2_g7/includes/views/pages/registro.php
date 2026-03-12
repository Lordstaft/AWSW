<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../class/forms/FormularioCrearUsuario.php';

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioCrearUsuario();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Crear Usuario</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';