<?php
//Inicio del procesamiento
require __DIR__ . '/../../includes/config.php';

use es\ucm\fdi\aw\usuarios\FormularioBusquedaUsuarios;

$tituloPagina = 'Busqueda de usuarios';

$formulario = new FormularioBusquedaUsuarios();
$formulario->gestiona();

$resultados = $_SESSION['resultadosBusqueda'];

$contenidoPrincipal = <<<EOS
    <h1>Resultados de la búsqueda</h1>
    $resultados
EOS;

unset($_SESSION['resultadosBusqueda']);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Panel Administrador'];
$app->generaVista('/plantillas/plantilla.php', $params);