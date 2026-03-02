<?php

require __DIR__ . '/../../config.php';

//Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['esAdmin']);
unset($_SESSION['nombre']);

session_destroy();
$paginaTitulo = 'Logout';
$contenidoPrincipal = <<<EOS
	<h1>Hasta pronto!</h1>
	EOS;

require __DIR__ . '/plantilla.php';
