<?php
require __DIR__ . '/../../config.php';

use es\ucm\fdi\aw\Producto;
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();

$tituloPagina = "Carrito";

$carrito = $_SESSION['carrito'] ?? [];

$contenidoPrincipal = "<h2>Carrito</h2>";

$total = 0;

if (empty($carrito)) {

    $contenidoPrincipal .= "<p>Carrito vacío</p>";

} else {

    $contenidoPrincipal .= "<form method='POST' action='".$app->resuelve('/pedidos/actualizarCarrito.php')."'>";

    foreach ($carrito as $id => $cantidad) {

        $producto = Producto::buscaPorId($id);

        if (!$producto) continue;

        $subtotal = $producto['precio'] * $cantidad;
        $total += $subtotal;

        $contenidoPrincipal .= "
        <div style='border:1px solid #ccc; padding:10px; margin:10px;'>

            <p><b>{$producto['nombreProd']}</b></p>

            <p>Precio: {$producto['precio']} €</p>

            <p>
            Cantidad:
            <input type='number' name='cantidad[$id]' value='$cantidad' min='1'>
            </p>

            <p>
            Subtotal: $subtotal €
            </p>

            <a href='".$app->resuelve('/pedidos/eliminarCarrito.php')."?id=$id'>
                Eliminar
            </a>

        </div>
        ";
    }

    $contenidoPrincipal .= "<button>Actualizar carrito</button></form>";

    $contenidoPrincipal .= "<h3>Total: $total €</h3>";

    /* FORMULARIO PAGO */
    $contenidoPrincipal .= "
    <form action='".$app->resuelve('/pedidos/pagoPedido.php')."' method='POST'>

        <label>Tipo de pedido:</label>

        <select name='tipo'>
            <option value='recogida'>Recoger en local</option>
            <option value='domicilio'>A domicilio</option>
        </select>

        <br><br>

        <button>Pagar</button>

    </form>

    <br>

    <a href='".$app->resuelve('/pedidos/cancelarPedido.php')."'>
        Cancelar pedido
    </a>
    ";
}

require __DIR__ . '/plantilla.php';