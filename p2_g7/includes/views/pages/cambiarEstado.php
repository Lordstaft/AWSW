.<?php

require __DIR__ . '/../../config.php';

use es\ucm\fdi\aw\Aplicacion;

$conn = Aplicacion::getInstance()->getConexionBd();

$id = (int) $_GET['id'];
$estado = $_GET['estado'];

$query = sprintf(
"UPDATE pedidos SET estadoPedido='%s' WHERE idPedido=%d",
$conn->real_escape_string($estado),
$id
);

$conn->query($query);

header("Location: pedidosGerente.php");