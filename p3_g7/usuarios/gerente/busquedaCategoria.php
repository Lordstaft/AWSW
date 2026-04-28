<?php
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\productos\FormularioBusquedaCategoria;

$tituloPagina = 'Modificar categorías';

$formulario = new FormularioBusquedaCategoria();
$formularioHTML = $formulario->gestiona();

$resultado = $_SESSION['resultadosBusqueda'];

$contenidoPrincipal = <<<EOS
    <h2>Busqueda de categorías</h2>
    $resultado
EOS;

unset($_SESSION['resultadosBusqueda']);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);