<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\EstadoPedido;

class FormularioPedidosEnCocina extends Formulario{

    public function __construct() {
        parent::__construct('formPedidosEnCocina', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidos.php'),
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $filas = '';

        $cocinero = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
        $pedidosPendientes = Pedido::pedidosPendientesCocinero($cocinero->getId());

        $erroresCampos = self::generaErroresCampos(['estado'], $this->errores, 'span', array('class' => 'error'));

        if (!empty($pedidosPendientes) && is_array($pedidosPendientes)) {

            foreach ($pedidosPendientes as $p) {

                $productosPedido = Pedido::buscaProductos($p->getPedidoId());
                $productos = '';
                $estados = '';

                foreach ($productosPedido as $s) {
                    $productos .= "<ul>
                        <li>{$s->getNombreProd()}</li>
                    </ul>";
                }

                foreach (EstadoPedido::cases() as $estado) {

                    if ($p->getEstadoPedido() === $estado->value && $estado->value !== EstadoPedido::PENDIENTE->value) {

                        $estados .= "<option value='{$estado->value}' selected>{$estado->value}</option>";
                    } 
                    else {
                        $estados .= "<option value='{$estado->value}'>{$estado->value}</option>";
                    }
                }

                $filas .= "
                <tr>
                    <td>{$p->getPedidoId()}</td>
                    <td>{$p->getFechaPedido()}</td>
                    <td>$productos</td>
                    <td>
                        <select name='estado'>
                            $estados
                        </select>
                        {$erroresCampos['estado']}
                    </td>
                    <td>
                        <button type='submit' name='idPedido' value='{$p->getPedidoId()}'>
                            Modificar
                        </button>
                    </td>
                </tr>";
            }
        }
        else {
            $filas = "No hay pedidos pendientes de preparar.";
            return $filas;
        }

        $html = <<<EOF
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha de pedido</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    $filas
                </tbody>
            </table>
        EOF;

        return $html;
    }
        protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idPedido = trim($datos['idPedido'] ?? '');
        $idPedido = filter_var($idPedido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(!$idPedido || empty($idPedido)){
            $mensajes = ['Error al localizar el pedido.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidosPendientes.php'));
        }

        $estadoPedido = trim($datos['estado'] ?? '');
        $estadoPedido = filter_var($estadoPedido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(!$estadoPedido || empty($estadoPedido)){
            $this->errores['estado'] = 'El estado del pedido no puede estar vacío';
        }

        if(count($this->errores) === 0){
            $usuario = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
            $modificacion = Pedido::modificarAsignacion($idPedido, $usuario->getId(), $estadoPedido);

            if(!$modificacion){
                $this->errores[] = "Error al modificar el estado del pedido.";
            }

            else{
                $mensajes = ['Se ha actualizado el estado del pedido.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidosPendientes.php'));
            }
        }
    }
}
