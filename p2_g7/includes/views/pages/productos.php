<?php
require __DIR__ . '/../../config.php';

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
            <a href='index.php?pagina=editarProducto&id={$id}'>Editar</a>
			<a href='retirarProducto.php?id={$p['id']}' onclick=\"return confirm('¿Seguro que quieres retirar este producto?');\">Retirar</a>
    </td>
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

require __DIR__ . '/plantilla.php';
