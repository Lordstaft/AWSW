<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\productos\Producto;

$tituloPagina = "Carrito";

$contenidoPrincipal = "<h1>Carrito</h1>";

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_POST['actualizar']) && isset($_POST['cantidad'])) {
    foreach ($_POST['cantidad'] as $id => $cantidad) {
        $id = (int)$id;
        $cantidad = (int)$cantidad;

        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$id]);
        } else {
            $_SESSION['carrito'][$id] = $cantidad;
        }
    }
}

if (isset($_GET['eliminar'])) {
    $idEliminar = (int) $_GET['eliminar'];
    unset($_SESSION['carrito'][$idEliminar]);
}

if (empty($_SESSION['carrito'])) {
    $contenidoPrincipal .= "<p>No hay productos en el carrito</p>";
} else {
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

    foreach ($_SESSION['carrito'] as $id => $cantidad) {
        $producto = Producto::buscaPorId($id);

        if (!$producto) {
            continue;
        }

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
                <a href='".$app->resuelve('/pedidos/carrito.php')."?eliminar=$id'>Eliminar</a>
            </td>
        </tr>
        ";
    }

    $contenidoPrincipal .= "</table><br>";
    $contenidoPrincipal .= "<button type='submit' name='actualizar'>Actualizar carrito</button>";
    $contenidoPrincipal .= "</form>";
    $contenidoPrincipal .= "<h3>Total: $total €</h3>";

    $contenidoPrincipal .= "<p><a href='".$app->resuelve('/pedidos/pedido.php')."'>Seguir comprando</a></p>";
    $contenidoPrincipal .= "<p><a href='".$app->resuelve('/pedidos/confirmarPedido.php')."'>Confirmar pedido</a></p>";
}

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);