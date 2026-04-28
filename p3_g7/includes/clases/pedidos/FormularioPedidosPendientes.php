<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Usuario;


class FormularioPedidosPendientes extends Formulario
{

    public function __construct() {
        parent::__construct('formPedidosPendientes', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidosPendientes.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidos.php')
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){

        $pedidosPendientes = Pedido::pedidosPendientes();

        $filas = '';
        $opcion = '';

        if (!empty($pedidosPendientes) && is_array($pedidosPendientes)) {
            foreach ($pedidosPendientes as $p) {
                $filas .= "<tr>
                    <td>{$p->getTipo()}</td>
                    <td>{$p->getFechaPedido()}</td>
                    <td>{$p->getEstadoPedido()}</td>
                    <td>
                        <div>
                            <button type='submit' name='idPedido' value='{$p->getPedidoId()}'>Asignar</button>
                        </div>
                    </td>
                </tr>";
            }
        } 

        if($filas === ''){
            $html = <<<EOF
                <p>No hay pedidos.</p>
            EOF;
        }

        else{
            $html = <<<EOF
                <table>
                    <thead>
                        <tr>
                            <th>Tipo de pedido</th>
                            <th>Fecha de pedido</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        $filas
                    </tbody>
                </table>
            EOF;
        }

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

        else{
            $cocinero = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
            $asignacion = Pedido::asignarPedido($idPedido, $cocinero->getId());
            if (!$asignacion) {
                $mensajes = ['Error al intentar asignar el pedido.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
            }
        }
    }

}
