<?php

function mostrarSaludo() {
	if (isset($_SESSION['login']) && ($_SESSION['login']===true)) {
		return "Bienvenido, {$_SESSION['nombre']} <a href='" . RUTA_APP . "/includes/views/pages/logout.php'>(salir)</a>";
		
	} 
    else {
		return "Usuario desconocido. <a href='" . RUTA_APP . "/index.php'>Login</a>";
	}
}
?>
<header>
	<h1>Bistro FDI</h1>
	<div class="saludo"><?= mostrarSaludo(); ?></div>
</header>


