<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\EstadoPedido;

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
        $app = Aplicacion::getInstance();
        if (isset($datos['confirmarPedido'])){
            $totalPedido = 0;
            $tipoPedido = '';
            if($_SESSION['tipoPedido'] === 'llevar'){
                $tipoPedido = 'domicilio';
            }
            elseif($_SESSION['tipoPedido'] === 'local'){
                $tipoPedido = 'recogida';
            }
            $nombre = $_SESSION['nombreUsuario'];
            $usuario = Usuario::buscaUsuario($nombre);
            if (isset($_SESSION['idPedido'])) {
                Pedido::eliminarPedido($_SESSION['idPedido']);
            }
            $nuevoPedido = Pedido::crearPedido($usuario->getId(), $tipoPedido, EstadoPedido::RECIBIDO->value);

            if(!$nuevoPedido){
                $this->errores[] = "Se ha producido un error al intentar enviar el pedido";
            }
            else{
                foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
                    $producto = Producto::buscaPorId($idProducto);
                    $precio = $producto->getPrecio();
                    $total = $precio * $cantidad;
                    $totalPedido += $total;
                    $resul = Pedido::añadirProductoPedido($nuevoPedido->getPedidoId(), $producto->getId(), $cantidad, $producto->getPrecio(), $producto->getIva());

                    if(!$resul){
                        $mensajes = ['No ha sido posible gestionar el pedido, intentelo de nuevo mas tarde.'];
                        $app->putAtributoPeticion('mensajes', $mensajes);
                        Pedido::eliminarPedido($nuevoPedido->getPedidoId());
                        $app->redirige(Aplicacion::getInstance()->resuelve('/inicio.php'));
                    }
                }

                $idPedido = $nuevoPedido->getPedidoId();
                $_SESSION['idPedido'] = $idPedido;
                Pedido::actualizarPrecioPedido($idPedido, $totalPedido);
                $mensajes = ['Pedido recibido'];
                $app->putAtributoPeticion('mensajes', $mensajes);
            }
        }
        elseif(isset($datos['cancelarPedido'])){
            if (isset($_SESSION['idPedido'])) {
                $pedido = Pedido::editarEstadoPedido($_SESSION['idPedido'], EstadoPedido::CANCELADO->value);
                if(!$pedido){
                    $this->errores[] = "Se ha producido un error al cancelar el pedido";
                }
                unset($_SESSION['idPedido']);
            }
            unset($_SESSION['carrito']);
            unset($_SESSION['tipoPedido']);
            $mensajes = ['Se ha cancelado el pedido.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige(Aplicacion::getInstance()->resuelve('/inicio.php'));
        }
    }

}
