<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\ofertas\Oferta;

class FormularioCarrito extends Formulario
{
    public function __construct() {
        parent::__construct('formCarrito', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/carrito.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/pedidos/pagoPedido.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $filas = '';
        $subtotal = 0;

        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            return "<p>El carrito está vacío</p>";
        }

        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            $producto = Producto::buscaPorId($idProducto);

            $precio = (float)$producto->getPrecio();
            $iva = (int)$producto->getIva();
            $precioConIva = $precio * (1 + $iva / 100);

            $totalLinea = $precioConIva * $cantidad;
            $subtotal += $totalLinea;

            $filas .= "<tr>
                <td>{$producto->getNombreProd()}</td>
                <td>" . number_format($producto->getPrecio(), 2) . " €</td>
                <td>{$producto->getIva()}%</td>
                <td>{$cantidad}</td>
                <td>" . number_format($totalLinea, 2) . " €</td>
            </tr>";
        }

        $ofertasDisponibles = Oferta::ofertasDisponibles();
        $activadas = $_SESSION['ofertas_activadas'] ?? [];

        $bloqueOfertas = "<h3>Ofertas disponibles</h3>";
        $bloqueOfertas .= "<p>No hay ofertas disponibles.</p>";

        $descuentoTotal = 0;
        $detalleOfertas = '';

        if ($ofertasDisponibles) {
            $bloqueOfertas = "<h3>Ofertas disponibles</h3>";

            foreach ($ofertasDisponibles as $oferta) {
                $marcada = in_array($oferta->getIdOferta(), $activadas) ? 'checked' : '';

                $productosOferta = $oferta->getProductos();
                $partes = [];
                foreach ($productosOferta as $productoOferta) {
                    $partes[] = $productoOferta['nombreProd'] . " x" . $productoOferta['cantidad'];
                }
                $detalle = implode(', ', $partes);

                $resultado = self::calcularAplicacionOferta($_SESSION['carrito'], $oferta);

                $textoAplicacion = $resultado['veces'] > 0
                    ? "Aplicable {$resultado['veces']} vez/veces"
                    : "No aplicable";

                $bloqueOfertas .= "
                    <div style='border:1px solid #ccc; margin:10px 0; padding:10px;'>
                        <label>
                            <input type='checkbox' name='ofertas[]' value='{$oferta->getIdOferta()}' $marcada>
                            <strong>{$oferta->getNombre()}</strong>
                        </label>
                        <p>{$oferta->getDescripcion()}</p>
                        <p><strong>Incluye:</strong> {$detalle}</p>
                        <p><strong>Descuento:</strong> {$oferta->getDescuento()}%</p>
                        <p><strong>Estado en tu pedido:</strong> {$textoAplicacion}</p>
                    </div>
                ";

                if (in_array($oferta->getIdOferta(), $activadas) && $resultado['veces'] > 0) {
                    $descuentoTotal += $resultado['descuentoTotal'];
                    $detalleOfertas .= "<li>{$oferta->getNombre()} - {$resultado['veces']} vez/veces - -" . number_format($resultado['descuentoTotal'], 2) . " €</li>";
                }
            }
        }

        $totalFinal = $subtotal - $descuentoTotal;
        if ($totalFinal < 0) {
            $totalFinal = 0;
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $resumenOfertas = '';
        if ($detalleOfertas !== '') {
            $resumenOfertas = "<h3>Ofertas aplicadas</h3><ul>{$detalleOfertas}</ul>";
        }

        return <<<EOS
            $htmlErroresGlobales
            <fieldset>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio base</th>
                            <th>IVA</th>
                            <th>Cantidad</th>
                            <th>Total con IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        $filas
                    </tbody>
                </table>

                <p><strong>Subtotal sin descuentos:</strong> {$this->formatoEuros($subtotal)}</p>

                $bloqueOfertas

                $resumenOfertas

                <p><strong>Descuento aplicado:</strong> {$this->formatoEuros($descuentoTotal)}</p>
                <p><strong>Total final:</strong> {$this->formatoEuros($totalFinal)}</p>

                <button type="submit" name="actualizarOfertas">Actualizar ofertas</button>
                <button type="submit" name="confirmarPedido">Confirmar</button>
                <button type="submit" name="cancelarPedido">Cancelar</button>
            </fieldset>
        EOS;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        if (isset($datos['cancelarPedido'])) {
            unset($_SESSION['carrito']);
            unset($_SESSION['tipoPedido']);
            unset($_SESSION['ofertas_activadas']);
            $mensajes = ['Se ha cancelado el pedido.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige($app->resuelve('/inicio.php'));
        }

        if (isset($datos['actualizarOfertas']) || isset($datos['confirmarPedido'])) {
            $_SESSION['ofertas_activadas'] = $datos['ofertas'] ?? [];
        }
    }

    private static function calcularAplicacionOferta($carrito, $oferta)
    {
        $productosOferta = $oferta->getProductos();

        $veces = PHP_INT_MAX;
        $precioPack = 0;

        foreach ($productosOferta as $producto) {
            $idProd = (int)$producto['producto_id'];
            $cantidadNecesaria = (int)$producto['cantidad'];
            $cantidadCarrito = $carrito[$idProd] ?? 0;

            $vecesProducto = intdiv($cantidadCarrito, $cantidadNecesaria);
            $veces = min($veces, $vecesProducto);

            $precio = (float)$producto['precio'];
            $iva = (int)$producto['iva'];
            $precioConIva = $precio * (1 + $iva / 100);

            $precioPack += $precioConIva * $cantidadNecesaria;
        }

        if ($veces === PHP_INT_MAX || $veces <= 0) {
            return [
                'veces' => 0,
                'descuentoTotal' => 0
            ];
        }

        $descuentoUnitario = round($precioPack * ((float)$oferta->getDescuento() / 100), 2);

        return [
            'veces' => $veces,
            'descuentoTotal' => round($descuentoUnitario * $veces, 2)
        ];
    }

    private function formatoEuros($cantidad)
    {
        return number_format((float)$cantidad, 2) . " €";
    }
}