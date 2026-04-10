<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\EstadoPedido;

class FormularioPagar extends Formulario
{

    public function __construct() {
        parent::__construct('formPagar', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/pagoPedido.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/inicio.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos){

        $nombreTitular = $datos['nombreTitular'] ?? '';
        $numeroTarjeta = $datos['numeroTarjeta'] ?? '';
        $fechaCaducidad = $datos['fechaCaducidad'] ?? '';
        $cvv = $datos['cvv'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreTitular', 'numeroTarjeta', 'fechaCaducidad', 'cvv'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Datos de pago</legend>

                <div>
                    <label for="nombreTitular">Nombre del titular:</label>
                    <input id="nombreTitular" type="text" name="nombreTitular" value="$nombreTitular"/>
                    {$erroresCampos['nombreTitular']}
                </div>

                <div>
                    <label for="numeroTarjeta">Número de tarjeta:</label>
                    <input id="numeroTarjeta" type="text" name="numeroTarjeta" maxlength="16" placeholder="1234 5678 9012 3456" value="$numeroTarjeta"/>
                    {$erroresCampos['numeroTarjeta']}
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
                </div>

                <div>
                    <button type="submit" name="pagar">Pagar</button>
                </div>
                <div>
                    <button type="submit" name="cancelar">Cancelar</button>
                </div>
            </fieldset>
            EOF;
        return $html;
    }
    
    protected function procesaFormulario(&$datos){
        
        $this->errores = [];

        $nombreTitular = $datos['nombreTitular'] ?? '';
        $numeroTarjeta = $datos['numeroTarjeta'] ?? '';
        $fechaCaducidad = $datos['fechaCaducidad'] ?? '';
        $cvv = $datos['cvv'] ?? '';

        if ($nombreTitular === '') {
            $this->errores['nombreTitular'] = 'El nombre del titular no puede estar vacío';
        }

        if ($numeroTarjeta === '') {
            $this->errores['numeroTarjeta'] = 'El número de tarjeta no puede estar vacío';
        }

        elseif (!preg_match('/^[0-9]{16}$/', $numeroTarjeta)) {
            $this->errores['numeroTarjeta'] = 'El número de tarjeta debe tener 16 dígitos';
        }

        if ($fechaCaducidad === '') {
            $this->errores['fechaCaducidad'] = 'La fecha de caducidad es obligatoria';
        } 
        elseif ($fechaCaducidad < date('Y-m')) {
            $this->errores['fechaCaducidad'] = 'La tarjeta está caducada';
        }

        if ($cvv === '') {
            $this->errores['cvv'] = 'El CVV es obligatorio';
        }

        $app = Aplicacion::getInstance();
        if(isset($datos['pagar'])){
            if (count($this->errores) === 0) {
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

                    Pedido::actualizarPrecioPedido($nuevoPedido->getPedidoId(), $totalPedido);
                    unset($_SESSION['carrito']);
                    unset($_SESSION['tipoPedido']);
                    $mensajes = ['Pedido realizado con exito'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                }
            }
        }
        elseif(isset($datos['cancelar'])){
            $app->redirige(Aplicacion::getInstance()->resuelve('/pedidos/carrito.php'));
        }
    }

}
