<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioBusquedaUsuarios;

$tituloPagina = 'Búsqueda de Usuarios';

if (isset($_SESSION["esAdmin"])) {
    $formulario = new FormularioBusquedaUsuarios();
    $formulario->gestiona();

    $resultados = $_SESSION['resultadosBusqueda'];

    $contenidoPrincipal = <<<EOS
        <h2>Resultados de la búsqueda</h2>
        $resultados
    EOS;

    unset($_SESSION['resultadosBusqueda']);
}
else {
    $contenidoPrincipal = <<<EOS
        <h2>Acceso denegado</h2>
        <p>Debes iniciar sesión como administrador para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);