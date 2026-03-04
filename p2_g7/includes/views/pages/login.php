<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="CSS/estilo.css" />
</head>
<body>
<div id="contenedor">
<?php
require('cabecera.php');
require('sidebarIzq.php');
?>
<main>
	<article>
		<h1>Acceso al sistema</h1>
		<form action="procesarLogin.php" method="POST">
		<fieldset>
            <legend>Usuario y contraseña</legend>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
				<input id="nombreUsuario" type="text" name="nombreUsuario" />
            </div>
            <div>
                <label for="password">Password:</label>
				<input id="password" type="password" name="password" />
            </div>
            <div>
				<button type="submit" name="login">Entrar</button>
			</div>
		</fieldset>
		</form>
	</article>
</main>
<?php
require('sidebarDer.php');
require('pie.php');
?>
</div>
</body>
</html>
