<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\ofertas\Oferta;

$ofertas = Oferta::listarOfertas();

$filas = '';

if ($ofertas) {
    foreach ($ofertas as $oferta) {
        $estado = $oferta->estaDisponible() ? 'Sí' : 'No';
        $productos = $oferta->getProductos();

        $productosTexto = '';
        if ($productos && count($productos) > 0) {
            $partes = [];
            foreach ($productos as $producto) {
                $partes[] = $producto['nombreProd'] . ' x' . $producto['cantidad'];
            }
            $productosTexto = implode(', ', $partes);
        }
        else {
            $productosTexto = 'Sin productos';
        }

        $filas .= "<tr>
            <td>{$oferta->getNombre()}</td>
            <td>{$oferta->getDescripcion()}</td>
            <td>{$productosTexto}</td>
            <td>{$oferta->getFechaInicio()}</td>
            <td>{$oferta->getFechaFin()}</td>
            <td>{$oferta->getDescuento()}%</td>
            <td>{$estado}</td>
            <td>" . $oferta->precioPackConIva() . " €</td>
            <td>" . $oferta->precioFinalOferta() . " €</td>
            <td>
                <a href='".$app->resuelve('/ofertas/editarOferta.php')."?id={$oferta->getIdOferta()}'>Editar</a>
                <a href='".$app->resuelve('/ofertas/borrarOferta.php')."?id={$oferta->getIdOferta()}' onclick=\"return confirm('¿Seguro que quieres borrar esta oferta?');\">Borrar</a>
            </td>
        </tr>";
    }
}

$tituloPagina = 'Ofertas';
$contenidoPrincipal = <<<EOS
<h1>Listado de ofertas</h1>

<p>
    <a href="{$app->resuelve('/ofertas/crearOferta.php')}">Crear oferta</a>
</p>

<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Productos</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Descuento</th>
            <th>Disponible</th>
            <th>Precio pack</th>
            <th>Precio final</th>
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