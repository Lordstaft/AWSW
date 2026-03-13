<?php
require __DIR__ . '/../../config.php';

use es\ucm\fdi\aw\Categoria;
use es\ucm\fdi\aw\Producto;

$conn = es\ucm\fdi\aw\Aplicacion::getInstance()->getConexionBd();

/* tipo de pedido */
if(isset($_GET['pagina'])){
    $_SESSION['pedido'] = $_GET['pagina'];
}

$tituloPagina = "Realizar pedido";

$contenidoPrincipal = "<h2>Categorías</h2>";

$categorias = Categoria::listar();

$contenidoPrincipal .= "<ul>";

foreach ($categorias as $categoria){

$contenidoPrincipal .= "
<li>
<a href='pedido.php?categoria={$categoria['id']}'>
{$categoria['nombre']}
</a>
</li>
";

}

$contenidoPrincipal .= "</ul>";

/* mostrar productos */
if(isset($_GET['categoria'])){

$idCategoria = (int) $_GET['categoria'];

$query = "SELECT id, nombreProd, precio
          FROM productos
          WHERE categoria_id = $idCategoria
          AND disponible = 1";

$res = $conn->query($query);

$contenidoPrincipal .= "<h2>Productos</h2>";

while($fila = $res->fetch_assoc()){

$contenidoPrincipal .= "

<p>
<b>{$fila['nombreProd']}</b> - {$fila['precio']} €
</p>

<form action='añadirCarrito.php' method='GET'>

<input type='hidden' name='id' value='{$fila['id']}'>

Cantidad:
<input type='number' name='cantidad' value='1' min='1'>

<button type='submit'>Añadir al carrito</button>

</form>

";

}

}   

$contenidoPrincipal .= "<p><a href='carrito.php'>Ver carrito</a></p>";

require __DIR__ . '/plantilla.php';