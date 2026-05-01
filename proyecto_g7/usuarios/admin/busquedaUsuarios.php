<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioBusquedaUsuarios;

$tituloPagina = 'Búsqueda de Usuarios';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioBusquedaUsuarios();
    $formulario->gestiona();

    $resultados = $_SESSION['resultadosBusqueda'];

    $contenidoPrincipal = <<<EOS
        <h1>Resultados de la búsqueda</h1>
        $resultados
    EOS;

    unset($_SESSION['resultadosBusqueda']);
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como administrador para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);