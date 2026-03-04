<?php
//Inicio del procesamiento
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="/AWSW/p2_g7/css/estilo.css" />
</head>
<body>
<div id="contenedor">
<?php
require(dirname(__DIR__).'/partials/cabecera.php');
require(dirname(__DIR__).'/partials/sidebarIzq.php');
?>
	<main>
		<article>
			<?= $contenidoPrincipal ?>
		</article>
	</main>
<?php
require(dirname(__DIR__).'/partials/sidebarDer.php');
require(dirname(__DIR__).'/partials/pie.php');
?>
</div>
</body>
</html>

