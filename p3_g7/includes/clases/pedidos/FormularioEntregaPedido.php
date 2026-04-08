<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\pedidos\Pedido;


class FormularioEntregaPedido extends Formulario
{

    public function __construct() {
        parent::__construct('formEntregaPedido', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/camarero/gestionarEntregas.php')
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){

        $pedidosEntrega = Pedido::pedidosListosEntrega();

        $filas = '';

        if (!empty($pedidosEntrega) && is_array($pedidosEntrega)) {
            foreach ($pedidosEntrega as $p) {
                $filas .= "<tr>
                    <td>{$p->getTipo()}</td>
                    <td>{$p->getFechaPedido()}</td>
                    <td>{$p->getEstadoPedido()}</td>
                    <td>
                        <div>
                            <button type='submit'>Entregar</button>
                            <input type='hidden' name='idPedido' value='{$p->getPedidoId()}'>
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
                <table border="1">
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
            $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/camarero/gestionarEntregas.php'));
        }

        else{
            $pedido = Pedido::realizarEntrega($idPedido);

            if(!$pedido){
                $mensajes = ['Error al localizar el pedido.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/camarero/gestionarEntregas.php'));
            }

            else{
                $mensajes = ['Pedido entregado con exito.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                $app->redirige(Aplicacion::getInstance()->resuelve('/usuarios/camarero/gestionarEntregas.php')); 
            }
        }
    }
}
