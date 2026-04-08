<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Roles;

class FormularioEditarAsignacion extends Formulario
{
    public function __construct() {
        parent::__construct('formPedidosAsignados', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/modificarAsignacionPedido.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/pedidosAsignados.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos){

        $idPedido = $datos['idPedido'] ?? $_POST['idPedido'] ?? $_GET['idPedido'];

        $cocineros =  Usuario::buscaRolUsuariosAdmin(Roles::COCINERO->value);
        $pedido = Pedido::buscaPedido($idPedido);
        
        var_dump($pedido);
        $opciones = '';
        $opcionesEstados = '';

        foreach($cocineros as $c){
            if($pedido->getCocineroId() === $c->getId()){
                $opciones .= "<option value='{$c->getId()}' selected>{$c->getNombre()}</option>";
            }
            else{
                $opciones .= "<option value='{$c->getId()}'>{$c->getNombre()}</option>";
            }
        }

        foreach(EstadoPedido::cases() as $estado){
            if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true && $estado->value === EstadoPedido::CANCELADO->value){
                if($pedido->getEstadoPedido() === $estado->value){
                    $opcionesEstados .= "<option value='{$estado->value}' selected>{$estado->value}</option>";
                }
                else{
                    $opcionesEstados .= "<option value='{$estado->value}'>{$estado->value}</option>";
                }
            }
            else{
                if($pedido->getEstadoPedido() === $estado->value){
                    $opcionesEstados .= "<option value='{$estado->value}' selected>{$estado->value}</option>";
                }
                else{
                    $opcionesEstados .= "<option value='{$estado->value}'>{$estado->value}</option>";
                }
            }
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Modificar asignación de pedido</legend>
                <div>
                    <label for="cocinero">Cocinero asignado:</label>
                    <select id="cocinero" name="cocinero" required>
                        $opciones
                    </select>
                </div>

                <div>
                    <label for="estado">Estado del pedido:</label>
                    <select id="estado" name="estado" required>
                        $opcionesEstados
                    </select>
                </div>

                <div>
                    <button type="submit" name = "GuardarCambios">Guardar cambios</button>
                    <input type="hidden" name="idPedido" value="{$pedido->getPedidoId()}">
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $this->errores = [];
        $idPedido = trim($datos['idPedido'] ?? '');
        $idPedido = filter_var($idPedido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(!$idPedido || empty($idPedido)){
            $mensajes = ['Error al localizar el pedido.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige('/usuarios/gerente/pedidosAsignados.php');
        }

        $estadoPedido = trim($datos['estado'] ?? '');
        $estadoPedido = filter_var($estadoPedido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $cocinero = trim($datos['cocinero'] ?? '');
        $cocinero = filter_var($cocinero, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $cocineroId = Usuario::buscaUsuarioId($cocinero);

        $modificacion = Pedido::modificarAsignacion($idPedido, $cocineroId->getId(), $estadoPedido);

        if(!$modificacion){
            $this->errores[] = 'Error al modificar la asignación del pedido.';
        }
        else{
            $mensajes = ['Se ha modificado la asignación del pedido correctamente.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
        }
    }

}