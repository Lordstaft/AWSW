<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\pedidos\Pedido;

class FormularioPedidosAsignados extends Formulario
{

    public function __construct() {
        parent::__construct('formPedidosAsignados', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/pedidosAsignados.php')
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){
        
        $pedidos = '';

        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
            $pedidos = Pedido::pedidosPendientes_Asignados(true);
        }

        else{
            $pedidos = Pedido::pedidosPendientes_Asignados(false);
        }

        $filas = '';

        if (!empty($pedidos) && is_array($pedidos)) {
            foreach ($pedidos as $p) {
                $filas .= "<tr>
                    <td>{$p->getPedidoId()}</td>
                    <td>{$p->getTipo()}</td>
                    <td>{$p->getFechaPedido()}</td>
                    <td>{$p->getEstadoPedido()}</td>
                    <td>
                        <div>
                            <button type='submit' name='idPedido' value='{$p->getPedidoId()}'>Modificar</button>
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
                <table class="tabla-general">
                    <thead>
                        <tr>
                            <th>Id pedido</th>
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
            $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/gerente/pedidosAsignados.php'));
        }

        $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/gerente/modificarAsignacionPedido.php?idPedido='.$idPedido));
    }

}
