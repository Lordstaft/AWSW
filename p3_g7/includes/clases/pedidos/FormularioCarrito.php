<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioCarrito extends Formulario
{

    public function __construct() {
        parent::__construct('formCarrito', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/carrito.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/pedidos/pagoPedido.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos){

        $filas = '';
        $totalPedido = 0;

        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            $html = "<p>El carrito está vacío</p>";
            return $html;
        }

        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {

            $producto = Producto::buscaPorId($idProducto);

            $precio = $producto->getPrecio();
            $total = $precio * $cantidad;
            $totalPedido += $total;

            $filas .= "<tr>
                <td>{$producto->getNombreProd()}</td>
                <td>{$producto->getPrecio()}€</td>
                <td>{$producto->getIva()}%</td>
                <td>{$cantidad}</td>
                <td>{$total}€</td>
            </tr>";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOS
            $htmlErroresGlobales
            <fieldset>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>IVA</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        $filas
                    </tbody>
                </table>

                <p><strong>Total del pedido: {$totalPedido}€</strong></p>

                <button type="submit" name="confirmarPedido">Confirmar</button>
                <button type="submit" name="cancelarPedido">Cancelar</button>
            </fieldset>
        EOS;
        return $html;
    }
    
    protected function procesaFormulario(&$datos){

        if(isset($datos['cancelarPedido'])){
            $app = Aplicacion::getInstance();
            unset($_SESSION['carrito']);
            unset($_SESSION['tipoPedido']);
            $mensajes = ['Se ha cancelado el pedido.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige(Aplicacion::getInstance()->resuelve('/inicio.php'));
        }
    }

}