<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\EstadoPedido;

// FUNCIONALIDAD 4:
// Se importa la clase Oferta para poder consultar ofertas activas,
// calcular descuentos y guardar las ofertas aplicadas al pedido.
use es\ucm\fdi\aw\ofertas\Oferta;

class FormularioPagar extends Formulario
{
    public function __construct() {
        parent::__construct('formPagar', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/pagoPedido.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombreTitular  = $datos['nombreTitular']  ?? '';
        $numeroTarjeta  = $datos['numeroTarjeta']  ?? '';
        $fechaCaducidad = $datos['fechaCaducidad'] ?? '';
        $cvv            = $datos['cvv']            ?? '';

        $carrito = $_SESSION['carrito'] ?? [];
        $subtotal = 0;
        $resumenProductos = '';

        // Se calcula el subtotal del carrito teniendo en cuenta el IVA de cada producto.
        foreach ($carrito as $idProducto => $cantidad) {
            $producto = Producto::buscaPorId($idProducto);
            if (!$producto) continue;

            $precioConIva = (float)$producto->getPrecio() * (1 + (int)$producto->getIva() / 100);
            $totalLinea = $precioConIva * $cantidad;
            $subtotal += $totalLinea;

            $resumenProductos .= "<li>{$producto->getNombreProd()} x{$cantidad} — " . number_format($totalLinea, 2) . " €</li>";
        }

        // FUNCIONALIDAD 4:
        // Variables necesarias para mostrar el descuento aplicado por una oferta.
        $descuentoTotal = 0;
        $resumenOferta  = '';

        // La oferta seleccionada por el usuario se guarda previamente en sesión.
        $ofertaActivada = $_SESSION['oferta_activada'] ?? null;

        // Si hay una oferta activada, se comprueba que exista y que esté disponible.
        if ($ofertaActivada) {
            $oferta = Oferta::buscaOferta($ofertaActivada);

            if ($oferta && $oferta->estaDisponible()) {

                // FUNCIONALIDAD 4:
                // Se calcula el descuento total que produce la oferta sobre el carrito actual.
                $resultado = Oferta::calcularOfertasAplicadas($carrito, [$ofertaActivada]);
                $descuentoTotal = $resultado['descuentoTotal'];

                // Si la oferta genera descuento, se muestra en el resumen del pedido.
                if ($descuentoTotal > 0) {
                    $resumenOferta = "<p>Oferta aplicada: <strong>{$oferta->getNombre()}</strong> — Descuento: -" . number_format($descuentoTotal, 2) . " €</p>";
                }
            }
        }

        // FUNCIONALIDAD 4:
        // El total final ya no es solo la suma del carrito,
        // sino el subtotal menos los descuentos aplicados.
        $totalFinal = max(0, $subtotal - $descuentoTotal);

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(
            ['nombreTitular', 'numeroTarjeta', 'fechaCaducidad', 'cvv'],
            $this->errores, 'span', ['class' => 'error']
        );

        return <<<EOF
            $htmlErroresGlobales

            <!-- FUNCIONALIDAD 4:
                 Se añade un resumen del pedido antes de pagar,
                 mostrando productos, subtotal, oferta aplicada,
                 descuento y total final. -->
            <fieldset>
                <legend>Resumen del pedido</legend>
                <ul>$resumenProductos</ul>
                <p><strong>Subtotal sin descuentos:</strong> {$this->formatoEuros($subtotal)}</p>
                $resumenOferta
                <p><strong>Descuento:</strong> -{$this->formatoEuros($descuentoTotal)}</p>
                <p><strong>Total a pagar:</strong> {$this->formatoEuros($totalFinal)}</p>
            </fieldset>

            <fieldset>
                <legend>Datos de pago</legend>

                <div>
                    <label for="nombreTitular">Nombre del titular:</label>
                    <input id="nombreTitular" type="text" name="nombreTitular" value="$nombreTitular"/>
                    {$erroresCampos['nombreTitular']}
                </div>

                <div>
                    <label for="numeroTarjeta">Número de tarjeta:</label>
                    <input id="numeroTarjeta" type="text" name="numeroTarjeta" maxlength="16" placeholder="1234567890123456" value="$numeroTarjeta"/>
                    {$erroresCampos['numeroTarjeta']}
                    <span id="formatoTarjeta"></span>
                </div>

                <div>
                    <label for="fechaCaducidad">Fecha de caducidad:</label>
                    <input id="fechaCaducidad" type="month" name="fechaCaducidad" value="$fechaCaducidad"/>
                    {$erroresCampos['fechaCaducidad']}
                </div>

                <div>
                    <label for="cvv">CVV:</label>
                    <input id="cvv" type="password" name="cvv" maxlength="4" value="$cvv"/>
                    {$erroresCampos['cvv']}
                    <span id="formatoCvv"></span>
                </div>

                <div>
                    <button type="submit" name="pagar">Pagar</button>
                </div>

                <div>
                    <button type="submit" name="cancelar">Cancelar</button>
                </div>
            </fieldset>
        EOF;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        if (isset($datos['pagar'])) {

            $this->errores = [];

            $nombreTitular  = $datos['nombreTitular']  ?? '';
            $numeroTarjeta  = $datos['numeroTarjeta']  ?? '';
            $fechaCaducidad = $datos['fechaCaducidad'] ?? '';
            $cvv            = $datos['cvv']            ?? '';

            if ($nombreTitular === '')
                $this->errores['nombreTitular'] = 'El nombre del titular no puede estar vacío';

            if ($numeroTarjeta === '')
                $this->errores['numeroTarjeta'] = 'El número de tarjeta no puede estar vacío';
            elseif (!preg_match('/^[0-9]{16}$/', $numeroTarjeta))
                $this->errores['numeroTarjeta'] = 'El número de tarjeta debe tener 16 dígitos';

            if ($fechaCaducidad === '')
                $this->errores['fechaCaducidad'] = 'La fecha de caducidad es obligatoria';
            elseif ($fechaCaducidad < date('Y-m'))
                $this->errores['fechaCaducidad'] = 'La tarjeta está caducada';

            if ($cvv === '')
                $this->errores['cvv'] = 'El CVV es obligatorio';

            if (count($this->errores) === 0) {

                $carrito = $_SESSION['carrito'] ?? [];

                // Se calcula el subtotal del pedido antes de aplicar ofertas.
                $subtotalSinDescuento = 0;

                foreach ($carrito as $idProducto => $cantidad) {
                    $producto = Producto::buscaPorId($idProducto);
                    if (!$producto) continue;

                    $precioConIva = (float)$producto->getPrecio() * (1 + (int)$producto->getIva() / 100);
                    $subtotalSinDescuento += $precioConIva * $cantidad;
                }

                // FUNCIONALIDAD 4:
                // Variables para guardar el descuento total y el detalle de las ofertas aplicadas.
                $descuentoAplicado = 0;
                $ofertasAplicadasDetalle = [];

                $ofertaActivada = $_SESSION['oferta_activada'] ?? null;

                // Se vuelve a validar la oferta en el momento del pago.
                // Esto evita aplicar ofertas caducadas o no disponibles.
                if ($ofertaActivada) {
                    $oferta = Oferta::buscaOferta($ofertaActivada);

                    if ($oferta && $oferta->estaDisponible()) {

                        // FUNCIONALIDAD 4:
                        // Se calcula cuántas veces se aplica la oferta
                        // y cuánto descuento genera.
                        $resultado = Oferta::calcularOfertasAplicadas($carrito, [$ofertaActivada]);

                        $descuentoAplicado = $resultado['descuentoTotal'];
                        $ofertasAplicadasDetalle = $resultado['ofertasAplicadas'];
                    }
                }

                // FUNCIONALIDAD 4:
                // Precio final del pedido después de aplicar descuentos.
                $total = max(0, $subtotalSinDescuento - $descuentoAplicado);

                $tipoPedido = 'recogida';
                if (($_SESSION['tipoPedido'] ?? '') === 'llevar') {
                    $tipoPedido = 'domicilio';
                }

                $usuario = Usuario::buscaUsuario($_SESSION['nombreUsuario']);

                // FUNCIONALIDAD 4:
                // Ahora el pedido se crea guardando también:
                // - subtotal sin descuento
                // - descuento aplicado
                // - total final
                $nuevoPedidoId = Pedido::crearPedido(
                    $usuario->getId(),
                    $tipoPedido,
                    EstadoPedido::NUEVO->value,
                    $subtotalSinDescuento,
                    $descuentoAplicado,
                    $total
                );

                if (!$nuevoPedidoId) {
                    $this->errores[] = "Se ha producido un error al intentar enviar el pedido";
                    return;
                }

                // Se insertan los productos comprados dentro del pedido.
                foreach ($carrito as $idProducto => $cantidad) {
                    $producto = Producto::buscaPorId($idProducto);
                    if (!$producto) continue;

                    $resul = Pedido::añadirProductoPedido(
                        $nuevoPedidoId,
                        $producto->getId(),
                        $cantidad,
                        $producto->getPrecio(),
                        $producto->getIva()
                    );

                    if (!$resul) {
                        $app->putAtributoPeticion('mensajes', ['No ha sido posible gestionar el pedido, inténtelo de nuevo más tarde.']);
                        Pedido::eliminarPedido($nuevoPedidoId);
                        $app->redirige($app->resuelve('/index.php'));
                        return;
                    }
                }

                // FUNCIONALIDAD 4:
                // Se guardan en la base de datos las ofertas aplicadas al pedido.
                // Esto permite saber posteriormente qué descuento tuvo el pedido
                // y cuántas veces se aplicó cada oferta.
                foreach ($ofertasAplicadasDetalle as $ofertaAplicada) {
                    Pedido::insertarOfertaPedido(
                        $nuevoPedidoId,
                        $ofertaAplicada['oferta_id'],
                        $ofertaAplicada['veces'],
                        $ofertaAplicada['descuento']
                    );
                }

                // Se limpia la sesión una vez finalizado el pedido.
                // También se elimina la oferta activada.
                unset($_SESSION['carrito'], $_SESSION['tipoPedido'], $_SESSION['oferta_activada']);

                $app->putAtributoPeticion('mensajes', ['Pedido realizado con éxito']);
            }

        }
        elseif (isset($datos['cancelar'])) {
            $app->redirige(Aplicacion::getInstance()->resuelve('/pedidos/carrito.php'));
        }
    }

    // Método auxiliar para mostrar cantidades en formato de euros.
    private function formatoEuros($cantidad)
    {
        return number_format((float)$cantidad, 2) . " €";
    }
}