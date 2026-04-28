<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;

class FormularioRealizarPedido extends Formulario
{

    public function __construct() {
        parent::__construct('formRealizarPedido', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/realizarPedido.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/pedidos/carrito.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos){

        $tipoPedidoGet = $_GET['pedido'] ?? '';

        // Solo bloquea si hay productos en el carrito Y el tipo es distinto
        if (!empty($_SESSION['carrito']) && isset($_SESSION['tipoPedido']) && $_SESSION['tipoPedido'] !== $tipoPedidoGet) {
            $app = Aplicacion::getInstance();
            $mensajes = ["Debes completar tu pedido para {$_SESSION['tipoPedido']}, revisa tu carrito."];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige(Aplicacion::getInstance()->resuelve('/index.php'));
        }

        $realizarPedido = $_SESSION['tipoPedido'] ?? $tipoPedidoGet;
        $_SESSION['tipoPedido'] = $realizarPedido;

        $tipoPedido = ($realizarPedido === 'local') ? "para consumir en local" : "para llevar";

        $productos = Producto::listarProductosCliente();

        $filas = '';

        if (!empty($productos) && is_array($productos)) {
            foreach ($productos as $p) {
                $filas .= "<tr>
                    <td><img class='img-producto-tabla' src=". Aplicacion::getInstance()->resuelve("/img/" . $p->getRutaImagen()) . "></td>
                    <td>{$p->getNombreProd()}</td>
                    <td>{$p->getDescripcion()}</td>
                    <td>{$p->getPrecio()}€</td>
                    <td>
                        <input type='number' name='productos[{$p->getId()}]' min='0' value='0'>
                    </td>
                </tr>";
            }
        } else {
            $filas = null;
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        if ($filas !== null) {
            $html = <<<EOS
                <h1>Pedido {$tipoPedido}</h1>

                $htmlErroresGlobales
                <fieldset>
                    <table>
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            $filas
                        </tbody>
                    </table>

                    <button type="submit" name="realizarPedido">Realizar pedido</button>
                </fieldset>
            EOS;
        } else {
            $html = <<<EOS
                <h1>Pedido {$tipoPedido}</h1>
                <p>En estos momentos no podemos procesar el pedido, intentelo mas tarde.</p>
            EOS;
        }

        return $html;
    }
    
    protected function procesaFormulario(&$datos){

        $this->errores = [];

        if (isset($datos['realizarPedido'])) {

            $productos = (array)($datos['productos'] ?? []);

            $carrito = [];

            foreach ($productos as $idProducto => $cantidad) {

                $cantidad = (int)$cantidad;

                if ($cantidad > 0) {
                    $carrito[$idProducto] = $cantidad;
                }
            }

            if (empty($carrito)) {
                $this->errores[] = "Debes seleccionar al menos un producto.";
            }

            $_SESSION['carrito'] = $carrito;
        }
    }

}