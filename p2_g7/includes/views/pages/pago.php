<?php
require __DIR__ . '/../../config.php';

$tituloPagina = "Pago";

$contenidoPrincipal = <<<EOS

<h1>Pago</h1>

<form action="confirmarPedido.php" method="POST">

Número tarjeta:
<input type="text" name="tarjeta" required>

<br><br>

Titular:
<input type="text" name="titular" required>

<br><br>

<button type="submit">Pagar</button>

</form>

<p>
<a href="confirmarPedido.php">Pagar al camarero</a>
</p>

EOS;

require __DIR__ . '/plantilla.php';