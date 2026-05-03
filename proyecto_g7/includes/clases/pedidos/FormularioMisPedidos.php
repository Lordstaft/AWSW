<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioMisPedidos extends Formulario {

    public function __construct() {
        parent::__construct('formMisPedidos', [
            'action' => Aplicacion::getInstance()->resuelve('/pedidos/misPedidos.php'),
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        if (!isset($_SESSION['nombreUsuario'])) {
            return '<p>Debes iniciar sesion para ver tus pedidos.</p>';
        }

        $usuario = Usuario::buscaUsuario($_SESSION['nombreUsuario']);
        if (!$usuario) {
            return '<p>No se pudo cargar tu informacion de usuario.</p>';
        }

        $pedidosActivos    = Pedido::pedidosUsuario($usuario->getId(), true);
        $pedidosHistorico  = Pedido::pedidosUsuario($usuario->getId(), false);

        $construyeFilas = function($pedidos){
            $filas = '';
            if (empty($pedidos)) {
                return null;
            }
            foreach ($pedidos as $p) {
                $estadoLabel = $p->getEstadoPedido();
                $tipo        = $p->getTipo() === 'domicilio' ? 'A domicilio' : 'Recogida en local';
                $total       = number_format((float)$p->getTotal(), 2, ',', '.') . '&euro;';
                
                // Obtener platos del pedido para mostrar detalle al cliente
                $platosInfo = Pedido::buscaProductosCocina($p->getPedidoId());
                $platosHtml = '<ul>';
                foreach ($platosInfo as $item) {
                    $prod      = $item['producto'];
                    $cant      = $item['cantidad'];
                    $preparado = $item['preparado'];
                    $icono     = $preparado ? '✅' : '⬜';
                    $platosHtml .= "<li>{$icono} " . htmlspecialchars($prod->getNombreProd()) . " &times;{$cant}</li>";
                }
                $platosHtml .= '</ul>';

                $filas .= <<<EOF
                    <tr>
                        <td><strong>#{$p->getPedidoId()}</strong></td>
                        <td>{$p->getFechaPedido()}</td>
                        <td>{$tipo}</td>
                        <td>{$platosHtml}</td>
                        <td><span class="badge-estado estado-{$p->getEstadoPedido()}">{$estadoLabel}</span></td>
                        <td><strong>{$total}</strong></td>
                    </tr>
                EOF;
            }
            return $filas;
        };

        $cabecera = <<<EOF
            <thead>
                <tr>
                    <th>Id pedido</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Detalle de platos</th>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
        EOF;

        $filasActivos   = $construyeFilas($pedidosActivos);
        $filasHistorico = $construyeFilas($pedidosHistorico);

        $seccionActivos   = $filasActivos
            ? "<div><table class='tabla-general'>{$cabecera}<tbody>{$filasActivos}</tbody></table></div>"
            : '<p>No tienes pedidos en curso en este momento.</p>';

        $seccionHistorico = $filasHistorico
            ? "<div class><table class='tabla-general'>{$cabecera}<tbody>{$filasHistorico}</tbody></table></div>"
            : '<p>No tienes pedidos en tu historial.</p>';

        $html = <<<EOF
            <section class="mis-pedidos">
                <h3>📦 Mis pedidos en curso</h3>
                <p>Aquí puedes seguir el estado de tus pedidos y ver que platos estan siendo preparados.</p>
                {$seccionActivos}
                
                <h3>📜 Historial de pedidos</h3>
                {$seccionHistorico}
            </section>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {

    }
}
