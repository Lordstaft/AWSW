<?php
require __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\forms\FormularioContacto;

$tituloPagina = 'Contacto';
$formulario = new FormularioContacto();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h1>Contacto</h1>
    $formularioHTML
EOS;

require __DIR__ . '/plantilla.php';
