<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioEditarCategoria;

$tituloPagina = 'Editar Categoría';

if (isset($_SESSION["esAdmin"])) {
    $idCategoria = $_GET['id'] ?? 0;
    $idCategoria = (int)$idCategoria;

    $formulario = new FormularioEditarCategoria($idCategoria);
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h2>Editar categoría</h2>
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