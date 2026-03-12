<?php
//Inicio del procesamiento
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../class/forms/FormularioEditarUsuario.php';

$tituloPagina = 'Editar usuario';

$formulario = new FormularioEditarUsuario();
$formularioHTML = $formulario->gestiona();


$contenidoPrincipal = <<<EOS
    <h1>Editar usuario</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';