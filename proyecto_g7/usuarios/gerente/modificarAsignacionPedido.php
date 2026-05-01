<?php
require __DIR__ . '/../../includes/config.php';
use es\ucm\fdi\aw\pedidos\FormularioEditarAsignacion;

$tituloPagina = 'Modificar Asignación de Pedido';

if (isset($_SESSION["esGerente"])) {
    $formulario = new FormularioEditarAsignacion();
    $formularioHTML = $formulario->gestiona();

    $contenidoPrincipal = <<<EOS
        <h1>Modificar asignación de pedido</h1>
        $formularioHTML
    EOS;
}
else {
    $contenidoPrincipal = <<<EOS
        <h1>Acceso denegado</h1>
        <p>Debes iniciar sesión como gerente para ver el contenido.</p>
    EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);