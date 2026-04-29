<?php
require __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\usuarios\FormularioCrearUsuario;

if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
    $cabecera = 'Panel Administrador';
}

else{
    $cabecera = 'Bistro FDI';
}

$tituloPagina = 'Registro';
$formulario = new FormularioCrearUsuario();
$formularioHTML = $formulario->gestiona();

$contenidoPrincipal = <<<EOS
    <h2>Crear Usuario</h2>
    $formularioHTML
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => $cabecera];
$app->generaVista('/plantillas/plantilla.php', $params);