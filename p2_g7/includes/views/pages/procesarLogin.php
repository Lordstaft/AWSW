<?php
//Inicio del procesamiento
require __DIR__ . '/../../config.php';

$formEnviado = isset($_POST['login']);
if (! $formEnviado ) {
	header('Location: login.php');
	exit();
}

require_once __DIR__. '/../../utils.php';

$erroresFormulario = [];

$nombreUsuario = filter_input(INPUT_POST, 'nombreUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $nombreUsuario || empty($nombreUsuario=trim($nombreUsuario)) ) {
	$erroresFormulario['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
}

$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ( ! $password || empty($password=trim($password)) ) {
	$erroresFormulario['password'] = 'El password no puede estar vacío.';
}

if (count($erroresFormulario) === 0) {
	$usuario = Usuario::login($nombreUsuario, $password);
	if ( $usuario ) {
		$_SESSION['login'] = true;
		$_SESSION['nombre'] = $usuario->getNombre();
		$_SESSION['esAdmin'] = $usuario->getRol() === Usuario::ADMIN_ROLE;
		header('Location: ./inicio.php');
		exit();
	}
	else {
		$erroresFormulario[] = 'El usuario o el password no son correctos';
	}
}

$tituloPagina = 'Login';
$erroresGlobalesFormulario = generaErroresGlobalesFormulario($erroresFormulario);
$erroresCampos = generaErroresCampos(['nombreUsuario', 'password'], $erroresFormulario);

$contenidoPrincipal= <<<EOS
	<h1>Acceso al sistema</h1>
	$erroresGlobalesFormulario
	<form action="procesarLogin.php" method="POST">
	<fieldset>
		<legend>Usuario y contraseña</legend>
		<div>
			<label for="nombreUsuario">Nombre de usuario:</label>
			<input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
			{$erroresCampos['nombreUsuario']}
		</div>
		<div>
			<label for="password">Password:</label>
			<input id="password" type="password" name="password" value="$password" />
			{$erroresCampos['password']}
		</div>
		<div>
			<button type="submit" name="login">Entrar</button>
		</div>
	</fieldset>
	</form>
EOS;

require __DIR__ . '/plantilla.php';