<?php
function mostrarSaludo() {
	if (isset($_SESSION['login']) && ($_SESSION['login']===true)) {
		return "Bienvenido, {$_SESSION['nombre']} <a href='/ej2_p12/01-inicio/01-inicio/01-inicio/includes/views/plantillas/logout.php'>(salir)</a>";
		
	} 
    else {
		return "Usuario desconocido.";
	}
}
?>
<header>
	<h1>Bistro FDI</h1>
	<div class="saludo"><?= mostrarSaludo(); ?></div>
</header>

<?php

