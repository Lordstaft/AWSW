<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioPerfil;

$tituloPagina = 'Ver perfil';

if (isset($_SESSION["login"])) {
    $formulario = new FormularioPerfil();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Perfil</h2>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);
