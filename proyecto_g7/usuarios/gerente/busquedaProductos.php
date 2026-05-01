<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\productos\FormularioBusquedaProductos;

$tituloPagina = 'Búsqueda de Productos';

if (isset($_SESSION["esGerente"])) {
    $formulario = new FormularioBusquedaProductos();
    $formularioHTML = $formulario->gestiona();

    $resultados = $_SESSION['resultadosBusqueda'];

    $contenidoPrincipal = <<<EOS
        <h1>Búsqueda de productos</h1>
        $resultados
    EOS;

    unset($_SESSION['resultadosBusqueda']);
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como gerente para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);