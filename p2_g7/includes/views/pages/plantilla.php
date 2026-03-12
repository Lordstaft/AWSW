<?php
//Inicio del procesamiento
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>estilo.css" />
</head>
<body>
<div id="contenedor">
<?php
require(dirname(__DIR__).'/partials/cabecera.php');
require(dirname(__DIR__).'/partials/sideBarIzq.php');
?>
	<main>
		<article>
			<?= $contenidoPrincipal ?>
		</article>
	</main>
<?php
require(dirname(__DIR__).'/partials/sideBarDer.php');
require(dirname(__DIR__).'/partials/pie.php');
?>
</div>
</body>
</html>

