<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioEditarUsuario;

$tituloPagina = 'Editar Usuario';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioEditarUsuario();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Editar usuario</h1>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como administrador para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);