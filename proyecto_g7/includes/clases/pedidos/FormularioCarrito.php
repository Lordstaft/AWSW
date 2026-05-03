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

        // Si no hay productos en el carrito, se informa al usuario.
        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            return "<p>El carrito está vacío</p>";
        }

        // Se recorren los productos del carrito para calcular el subtotal.
        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            $producto = Producto::buscaPorId($idProducto);

            // Cálculo del precio con IVA.
            $precio = (float)$producto->getPrecio();
            $iva = (int)$producto->getIva();
            $precioConIva = $precio * (1 + $iva / 100);

            // Cálculo del total por línea de producto.
            $totalLinea = $precioConIva * $cantidad;
            $subtotal += $totalLinea;

            // Generación de la fila de la tabla.
            $filas .= "<tr>
                <td>{$producto->getNombreProd()}</td>
                <td>" . number_format($producto->getPrecio(), 2) . " €</td>
                <td>{$producto->getIva()}%</td>
                <td>{$cantidad}</td>
                <td>" . number_format($totalLinea, 2) . " €</td>
            </tr>";
        }

        // FUNCIONALIDAD 4:
        // Se obtienen todas las ofertas disponibles actualmente en el sistema.
        $ofertasDisponibles = Oferta::ofertasDisponibles();

        // En la nueva versión solo se permite UNA oferta por pedido.
        // Por eso se usa una variable simple en sesión en lugar de un array.
        $activada = $_SESSION['oferta_activada'] ?? null;

        $bloqueOfertas = "<h3>Ofertas disponibles</h3><p>No hay ofertas disponibles actualmente.</p>";
        $descuentoTotal = 0;
        $detalleOfertas = '';

        if ($ofertasDisponibles) {

            $bloqueOfertas  = "<h3>Ofertas disponibles</h3>";

            // Se informa al usuario de la restricción:
            // solo se puede aplicar una oferta por pedido.
            $bloqueOfertas .= "<p><em>Solo se puede aplicar una oferta por pedido.</em></p>";

            foreach ($ofertasDisponibles as $oferta) {

                // Si esta oferta es la seleccionada, se marca el radio.
                $marcada = ($activada == $oferta->getIdOferta()) ? 'checked' : '';

                // Se obtienen los productos necesarios para que la oferta sea aplicable.
                $productosOferta = $oferta->getProductos();

                $partes = [];
                $precioPackDisplay = 0;

                // Se calcula el precio total del pack sin descuento.
                foreach ($productosOferta as $productoOferta) {

                    $partes[] = $productoOferta['nombreProd'] . " x" . $productoOferta['cantidad'];

                    $pIva = (float)$productoOferta['precio'] *
                            (1 + (int)$productoOferta['iva'] / 100);

                    $precioPackDisplay +=
                        $pIva * (int)$productoOferta['cantidad'];
                }

                $detalle = implode(', ', $partes);

                // Redondeo del precio sin descuento.
                $precioPackDisplay = round($precioPackDisplay, 2);

                // Cálculo del precio final con descuento.
                $precioFinalDisplay =
                    round(
                        $precioPackDisplay *
                        (1 - (float)$oferta->getDescuento() / 100),
                        2
                    );

                // FUNCIONALIDAD 4:
                // Se calcula cuántas veces se puede aplicar la oferta
                // y cuánto descuento produce en total.
                $resultado =
                    self::calcularAplicacionOferta(
                        $_SESSION['carrito'],
                        $oferta
                    );

                // Se muestra al usuario si la oferta es aplicable.
                $textoAplicacion =
                    $resultado['veces'] > 0
                    ? "&#x2705; Aplicable {$resultado['veces']} vez/veces (ahorro: "
                        . number_format($resultado['descuentoTotal'], 2) . " €)"
                    : "&#x274C; No aplicable con tu pedido actual";

                // Se genera el bloque visual de cada oferta.
                // Se usa RADIO en lugar de CHECKBOX.
                // Esto garantiza que solo se seleccione una oferta.
                $bloqueOfertas .= "
                    <div style='border:1px solid #ccc; margin:10px 0; padding:10px; border-radius:4px;'>

                        <label>
                            <input type='radio'
                                   name='oferta'
                                   value='{$oferta->getIdOferta()}'
                                   $marcada>

                            <strong>{$oferta->getNombre()}</strong>
                        </label>

                        <p>{$oferta->getDescripcion()}</p>

                        <p><strong>Incluye:</strong> {$detalle}</p>

                        <p>
                            Precio sin descuento:
                            " . number_format($precioPackDisplay, 2) . " €

                            | Descuento: {$oferta->getDescuento()}% |

                            Precio con oferta:
                            " . number_format($precioFinalDisplay, 2) . " €
                        </p>

                        <p>
                            <strong>Estado en tu pedido:</strong>
                            {$textoAplicacion}
                        </p>

                    </div>
                ";

                // Si la oferta está seleccionada y es aplicable,
                // se suma su descuento al total.
                if ($activada == $oferta->getIdOferta()
                    && $resultado['veces'] > 0) {

                    $descuentoTotal +=
                        $resultado['descuentoTotal'];

                    $detalleOfertas .=
                        "<li>
                        {$oferta->getNombre()}
                        - {$resultado['veces']} vez/veces
                        - -" .
                        number_format( $resultado['descuentoTotal'],2 ). " €
                        </li>";
                }
            }

            // Se añade la opción de no aplicar ninguna oferta.
            $sinOferta =
                ($activada === null || $activada === '')
                ? 'checked'
                : '';

            $bloqueOfertas .= "
                <div style='margin:10px 0;'>

                    <label>
                        <input type='radio' name='oferta' value='' $sinOferta>
                        <em>Sin oferta</em>
                    </label>

                </div>
            ";
        }

        // Cálculo del total final del pedido.
        $totalFinal = max(0, $subtotal - $descuentoTotal);

        $htmlErroresGlobales =
            self::generaListaErroresGlobales(
                $this->errores
            );

        $resumenOfertas =
            $detalleOfertas !== ''
            ? "<h3>Oferta aplicada</h3><ul>{$detalleOfertas}</ul>"
            : '';

        return <<<EOS

            $htmlErroresGlobales

            <fieldset>

                <h2>Tu carrito</h2>
                <div class="tabla-wrapper">
                    <table>
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
                </div>

                <p>
                    <strong>Subtotal sin descuentos:</strong>
                    {$this->formatoEuros($subtotal)}
                </p>

                $bloqueOfertas

                $resumenOfertas

                <p>
                    <strong>Descuento aplicado:</strong>
                    -{$this->formatoEuros($descuentoTotal)}
                </p>

                <p>
                    <strong>Total final:</strong>
                    {$this->formatoEuros($totalFinal)}
                </p>

                <button type="submit"
                        name="actualizarOfertas">
                    Actualizar oferta
                </button>

                <button type="submit"
                        name="confirmarPedido">
                    Confirmar y pagar
                </button>

                <button type="submit"
                        name="cancelarPedido">
                    Cancelar pedido
                </button>

            </fieldset>

        EOS;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        // Cancelación del pedido.
        if (isset($datos['cancelarPedido'])) {

            unset( $_SESSION['carrito'], $_SESSION['tipoPedido'], $_SESSION['oferta_activada'] );

            $app->putAtributoPeticion( 'mensajes', ['Se ha cancelado el pedido.'] );

            $app->redirige( $app->resuelve('/index.php') );
        }

        // FUNCIONALIDAD 4:
        // Se guarda la oferta seleccionada en la sesión.
        // Si el usuario elige "Sin oferta", se guarda null.
        if (isset($datos['actualizarOfertas'])
            || isset($datos['confirmarPedido'])) {

            $ofertaSeleccionada = $datos['oferta'] ?? '';

            $_SESSION['oferta_activada'] =
                ($ofertaSeleccionada !== '') ? (int)$ofertaSeleccionada : null;
        }
    }

    // FUNCIONALIDAD 4:
    // Este método calcula:
    // - cuántas veces se puede aplicar la oferta
    // - el descuento total generado
    private static function calcularAplicacionOferta( $carrito, $oferta)
    {
        $productosOferta = $oferta->getProductos();

        $veces = PHP_INT_MAX;
        $precioPack = 0;

        foreach ($productosOferta as $producto) {

            $cantidadNecesaria = (int)$producto['cantidad'];

            if ($cantidadNecesaria <= 0)
                continue;

            $cantidadCarrito = $carrito[ (int)$producto['producto_id']] ?? 0;

            // Se calcula el número máximo de packs posibles.
            $veces = min( $veces, intdiv( $cantidadCarrito, $cantidadNecesaria ) );

            // Cálculo del precio del pack.
            $precioConIva = (float)$producto['precio'] * (1 + (int)$producto['iva'] / 100);

            $precioPack += $precioConIva * $cantidadNecesaria;
        }

        if ($veces === PHP_INT_MAX || $veces <= 0) {

            return [
                'veces' => 0, 'descuentoTotal' => 0 ];
        }

        // Cálculo del descuento unitario.
        $descuentoUnitario =
            round( $precioPack * ( (float)$oferta->getDescuento() / 100 ), 2);

        return [ 'veces' => $veces, 'descuentoTotal' => round( $descuentoUnitario * $veces, 2 )];
    }

    private function formatoEuros($cantidad)
    {
        return number_format( (float)$cantidad, 2 ) . " €";
    }
}