<?php
require __DIR__ . '/../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioBusquedaUsuarios;

$tituloPagina = 'Buscar usuario';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioBusquedaUsuarios();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Buscar Usuario</h2>
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
