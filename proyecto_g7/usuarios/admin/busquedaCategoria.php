<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioBusquedaCategoria;

$tituloPagina = 'Búsqueda de Categorías';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioBusquedaCategoria();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Búsqueda de categorías</h2>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como administrados para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);