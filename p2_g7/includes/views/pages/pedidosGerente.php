<?php

require __DIR__ . '/../../config.php';

use es\ucm\fdi\aw\Aplicacion;

$conn = Aplicacion::getInstance()->getConexionBd();

$tituloPagina = "Pedidos";

$query = "
SELECT 
p.idPedido,
p.numPedido,
p.estadoPedido,
p.total,
p.fechaPedido,
u.nombre
FROM pedidos p
JOIN usuarios u ON p.usuario_id = u.id
ORDER BY p.fechaPedido DESC
";

$res = $conn->query($query);

$contenidoPrincipal = "<h2>Listado de pedidos</h2>";

while ($fila = $res->fetch_assoc()) {

$contenidoPrincipal .= "
<p>
Pedido {$fila['numPedido']} |
Cliente: {$fila['nombre']} |
{$fila['total']} € |
Estado: {$fila['estadoPedido']}

<a href='cambiarEstado.php?id={$fila['idPedido']}&estado=preparando'>Preparar</a>

<a href='cambiarEstado.php?id={$fila['idPedido']}&estado=entregado'>Entregar</a>

</p>
";

}

require __DIR__ . '/plantilla.php';