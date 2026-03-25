<?php
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Roles;

$app = Aplicacion::getInstance();
?>

<nav id="sidebarIzq">
	<h3>Navegación</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php') ?>">Inicio</a></li>

		<?php if (!$app->usuarioLogueado()) { ?>
			<li><a href="<?= $app->resuelve('/login.php') ?>">Login</a></li>
		<?php } ?>

		<?php if ($app->usuarioLogueado()) { ?>
			<li><a href="<?= $app->resuelve('/logout.php') ?>">Cerrar sesión</a></li>
		<?php } ?>

		<?php if ($app->tieneRol(Roles::COCINERO) || $app->tieneRol(Roles::ADMIN)) { ?>
			<li><a href="<?= $app->resuelve('/pedidos/cocina.php') ?>">Cocina</a></li>
		<?php } ?>

		<?php if ($app->tieneRol(Roles::GERENTE) || $app->tieneRol(Roles::ADMIN)) { ?>
			<li><a href="<?= $app->resuelve('/pedidos/gerente.php') ?>">Pedidos gerente</a></li>
		<?php } ?>
	</ul>
</nav>