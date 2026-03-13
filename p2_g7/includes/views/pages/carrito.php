<?php
require __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\Producto;

$tituloPagina = "Carrito";

$contenidoPrincipal = "<h1>Carrito</h1>";

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

/* actualizar cantidades */
if(isset($_POST['actualizar'])){

    foreach($_POST['cantidad'] as $id => $cantidad){

        if($cantidad <= 0){
            unset($_SESSION['carrito'][$id]);
        } else {
            $_SESSION['carrito'][$id] = (int)$cantidad;
        }

    }
}

/* eliminar producto */
if(isset($_GET['eliminar'])){
    $idEliminar = (int)$_GET['eliminar'];
    unset($_SESSION['carrito'][$idEliminar]);
}

if(empty($_SESSION['carrito'])){

    $contenidoPrincipal .= "<p>No hay productos en el carrito</p>";

}else{

$total = 0;

$contenidoPrincipal .= "<form method='POST'>";

$contenidoPrincipal .= "<table border='1'>
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
<th>Eliminar</th>
</tr>";

foreach($_SESSION['carrito'] as $id => $cantidad){

$producto = Producto::buscaPorId($id);

$precio = $producto['precio'];

$subtotal = $precio * $cantidad;

$total += $subtotal;

$contenidoPrincipal .= "
<tr>
<td>{$producto['nombreProd']}</td>
<td>{$precio} €</td>
<td>
<input type='number' name='cantidad[$id]' value='$cantidad' min='0'>
</td>
<td>$subtotal €</td>
<td>
<a href='carrito.php?eliminar=$id'>Eliminar</a>
</td>
</tr>
";

}

$contenidoPrincipal .= "</table>";

$contenidoPrincipal .= "<br>";

$contenidoPrincipal .= "<button type='submit' name='actualizar'>Actualizar carrito</button>";

$contenidoPrincipal .= "</form>";

$contenidoPrincipal .= "<h3>Total: $total €</h3>";

$contenidoPrincipal .= "
<br>
<a href='cancelarPedido.php'>Cancelar pedido</a>
";

$contenidoPrincipal .= "<a href='pago.php'>Proceder al pago</a>";

}

require __DIR__ . '/plantilla.php';