<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>
<nav id="sidebarIzq">
	<h3>Navegación</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		<li><a href="<?= $app->resuelve('/contenido.php')?>">Ver contenido</a></li>
		<li><a href="<?= $app->resuelve('/admin.php')?>">Administrar</a></li>
		<li><a href="<?= $app->resuelve('/cocina.php')?>">Panel cocina</a></li>
		<li><a href="<?= $app->resuelve('/pedidos/Gerente.php')?>">Pedidos gerente</a></li>
	</ul>
</nav>

