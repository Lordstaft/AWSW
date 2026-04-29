<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\EstadoPedido;

class FormularioPedidosEnCocina extends Formulario {

    public function __construct() {
        parent::__construct('formPedidosEnCocina', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/cocinero/pedidos.php'),
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $cocinero = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
        $pedidos  = Pedido::pedidosPendientesCocinero($cocinero->getId());

        if (empty($pedidos)) {
            return '<p>No hay pedidos pendientes de preparar.</p>';
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['estado'], $this->errores, 'span', array('class' => 'error'));
        $filas = '';

        foreach ($pedidos as $pedido) {

            $platosHtml     = $this->generaListaPlatos($pedido);
            $selectEstados  = $this->generaSelectEstados($pedido);

            $filas .= "
            <tr>
                <td>{$pedido->getPedidoId()}</td>
                <td>{$pedido->getFechaPedido()}</td>
                <td>{$platosHtml}</td>
                <td>
                    {$selectEstados}
                    {$erroresCampos['estado']}
                </td>
                <td>
                    <button type='submit' name='idPedido' value='{$pedido->getPedidoId()}'>
                        Actualizar
                    </button>
                </td>
            </tr>";
        }

        return <<<HTML
            $htmlErroresGlobales
            <table class="tabla-general">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Platos</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    $filas
                </tbody>
            </table>
        HTML;
    }

    /**
     * Genera la lista de platos de un pedido
     */
    private function generaListaPlatos($pedido)
    {
        $items = Pedido::buscaProductosCocina($pedido->getPedidoId());
        $html = '<ul class="lista-platos">';

        foreach ($items as $item) {
            $producto   = $item['producto'];
            $cantidad   = $item['cantidad'];
            $preparado  = $item['preparado'];

            $nombre = htmlspecialchars($producto->getNombreProd());
            $icono  = $preparado ? '✅' : '⬜';

            $boton = $preparado ? '' : "
                <button type='submit' name='marcarPlato[{$pedido->getPedidoId()}][{$producto->getId()}]' value='1' class='btn-plato'> listo </button>";

            $html .= "
                <li class='lista-platos'>
                    {$icono} {$nombre} x {$cantidad} {$boton}
                </li>";
        }

        return $html . '</ul>';
    }

    /**
     * Genera el select de estados permitidos
     */
    private function generaSelectEstados($pedido)
    {
        $estadoActual = $pedido->getEstadoPedido();
        $opciones = '';

        foreach (EstadoPedido::cases() as $estado) {

            // Estados NO permitidos para cocina
            if (in_array($estado->value, [
                EstadoPedido::CANCELADO->value,
                EstadoPedido::PENDIENTE->value,
                EstadoPedido::ENTREGADO->value,
                EstadoPedido::NUEVO->value
            ])) {
                continue;
            }

            $selected = ($estadoActual === $estado->value) ? 'selected' : '';

            $opciones .= "<option value='{$estado->value}' {$selected}>
                {$estado->value}
            </option>";
        }

        return "<select name='estado'>{$opciones}</select>";
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        if (isset($datos['marcarPlato']) && is_array($datos['marcarPlato'])) {
            foreach ($datos['marcarPlato'] as $pedidoId => $productos) {
                foreach ($productos as $productoId => $valor) {
                    if ((int)$valor === 1) {
                        Pedido::marcarPlatoPreparado((int)$pedidoId, (int)$productoId);
                    }
                }
            }

            $app->putAtributoPeticion('mensajes', ['Plato marcado como preparado.']);
            $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
            return;
        }

        $idPedido = filter_var($datos['idPedido'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        if (!$idPedido) {
            $app->putAtributoPeticion('mensajes', ['Error al localizar el pedido.']);
            $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
            return;
        }

        $estadoPedido = filter_var($datos['estado'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$estadoPedido) {
            $this->errores['estado'] = 'El estado del pedido no puede estar vacío';
            return;
        }

        if (empty($this->errores)) {
            $usuario = Usuario::buscaUsuario($_SESSION['nombreUsuario']);

            if(isset($datos['idPedido'])){
                $pedidoCorrecto = true;

                $items = Pedido::buscaProductosCocina($idPedido);

                foreach ($items as $item) {
                    if ((int)$item['preparado'] === 0) {
                        $pedidoCorrecto = false;
                        break;
                    }
                }



                if($estadoPedido !== EstadoPedido::LISTO->value && $pedidoCorrecto === false){
                    $ok = Pedido::modificarAsignacion(
                        (int)$idPedido,
                        $usuario->getId(),
                        $estadoPedido
                    );
                    if(!$ok) {
                        $this->errores[] = 'Error al modificar el estado del pedido.';
                    }
                    else {
                        $app->putAtributoPeticion('mensajes', ['Estado actualizado correctamente.']);
                        $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
                    }
                }

                elseif($estadoPedido === EstadoPedido::LISTO->value && $pedidoCorrecto === true){
                    $ok = Pedido::modificarAsignacion(
                        (int)$idPedido,
                        $usuario->getId(),
                        $estadoPedido
                    );
                    if(!$ok) {
                        $this->errores[] = 'Error al modificar el estado del pedido.';
                    }
                    else {
                        $app->putAtributoPeticion('mensajes', ['Estado actualizado correctamente.']);
                        $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
                    }
                }

                elseif($estadoPedido === EstadoPedido::LISTO->value && $pedidoCorrecto === false){
                    $this->errores[] = 'Error al modificar el pedido.';
                    $app->putAtributoPeticion('mensajes', ['Los platos no estan preparados.']);
                    $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
                }
                
                else{
                    $this->errores[] = 'Error al modificar el pedido.';
                    $app->putAtributoPeticion('mensajes', ['Los platos ya estan preparados, por favor actualice el estado del pedido a listo.']);
                    $app->redirige($app->resuelve('/usuarios/cocinero/pedidos.php'));
                }

            }
        }
    }
}