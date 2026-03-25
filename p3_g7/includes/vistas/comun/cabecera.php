<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();

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
    <div class="cabecera-contenido">
        <img src="<?= $app->resuelve('/img/logo.png') ?>" alt="Logo BistroFDI" class="logo">
        <h1>BistroFDI</h1>
    </div>
</header>