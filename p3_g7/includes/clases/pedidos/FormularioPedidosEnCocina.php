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

    }
}
