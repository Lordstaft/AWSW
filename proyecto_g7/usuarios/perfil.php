<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioPerfil;

$tituloPagina = 'Ver perfil';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioPerfil();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Perfil</h1>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);