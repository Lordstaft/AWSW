<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioEditarUsuario;

$tituloPagina = 'Editar Usuario';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioEditarUsuario();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Editar usuario</h2>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como administrador para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);