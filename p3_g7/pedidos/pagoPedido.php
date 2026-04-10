<?php
require __DIR__ . '/../../config.php';

use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();

$tituloPagina = "Pago";

/* Recibir tipo */
$tipo = $_POST['tipo'] ?? 'recogida';

$contenidoPrincipal = "
<h2>Pago</h2>

<form action='".$app->resuelve('/pedidos/confirmarPedido.php')."' method='POST'>

<input type='hidden' name='tipo' value='$tipo'>

<p>Número tarjeta:</p>
<input type='text' required>

<p>CVV:</p>
<input type='text' required>

<br><br>

<button>Pagar</button>

</form>
";

require __DIR__ . '/plantilla.php';