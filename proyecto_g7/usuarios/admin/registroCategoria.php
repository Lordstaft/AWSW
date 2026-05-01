<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioCrearCategoria;

$tituloPagina = 'Crear Categoría';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioCrearCategoria();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Crear categoría</h1>
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