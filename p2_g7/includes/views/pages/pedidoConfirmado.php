<?php
require __DIR__ . '/../../config.php';

$id = $_GET['id'];

$tituloPagina = "Pedido confirmado";

$contenidoPrincipal = <<<EOS
<h1>Pedido confirmado</h1>

<p>Tu pedido número <b>$id</b> ha sido registrado correctamente.</p>

<a href="inicio.php">Volver al inicio</a>
EOS;

require __DIR__ . '/plantilla.php';