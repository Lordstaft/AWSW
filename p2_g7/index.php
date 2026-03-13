<?php
//Inicio del procesamiento
require __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\forms\FormularioLogin;

$tituloPagina = 'Login';
$formulario = new FormularioLogin();
$formularioHTML = $formulario->gestiona();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
	header('Location: ' . RUTA_APP . '/includes/views/pages/inicio.php');
	exit();
}

$contenidoPrincipal = <<<EOS
	<h1>Acceso al sistema</h1>
	$formularioHTML
EOS;

require __DIR__ . '/includes/views/pages/plantilla.php';