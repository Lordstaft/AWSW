<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioPerfil;

$tituloPagina = 'Buscar usuario';
$formulario = new FormularioPerfil();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h2>Perfil</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);