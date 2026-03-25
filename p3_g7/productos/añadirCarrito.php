<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\productos\Producto;

$productos = Producto::listar();

$filas = '';
foreach ($productos as $p) {
    $disponible = $p['disponible'] ? 'Sí' : 'No';
    $ofertado = $p['ofertado'] ? 'Sí' : 'No';

    $filas .= "<tr>
        <td>{$p['nombreProd']}</td>
        <td>{$p['categoria']}</td>
        <td>{$p['precio']}</td>
        <td>{$p['iva']}%</td>
        <td>{$disponible}</td>
        <td>{$ofertado}</td>
        <td>
            <a href='".$app->resuelve('/editarProducto.php')."?id={$p['id']}'>Editar</a>
            <a href='".$app->resuelve('/productos/retirarProducto.php')."?id={$p['id']}' onclick=\"return confirm('¿Seguro que quieres retirar este producto?');\">Retirar</a>
        </td>
    </tr>";
}

$tituloPagina = 'Productos';
$contenidoPrincipal = <<<EOS
<h1>Listado de productos</h1>
<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio base</th>
            <th>IVA</th>
            <th>Disponible</th>
            <th>Ofertado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        $filas
    </tbody>
</table>
EOS;

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'BistroFDI'
];

$app->generaVista('/plantillas/plantilla.php', $params);