<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Formulario;

class FormularioCocina extends Formulario
{
    public function __construct()
    {
        parent::__construct('formCocina');
    }

    protected function generaCamposFormulario(&$datos)
{
    $pedidos = Cocina::getPedidos();

    $html = "<h1>Pedidos en cocina</h1>";

    if (!$pedidos || count($pedidos) == 0) {
        return $html . "No hay pedidos";
    }

    foreach ($pedidos as $pedido) {

        $pedidoId = $pedido['id'];
        $estado = $pedido['estado'];

        $html .= "<hr>";
        $html .= "<h2>Pedido $pedidoId</h2>";
        $html .= "Estado: $estado<br>";

        /* Botón asignar */

        if ($estado == 'recibido' || $estado == 'en_preparacion') {

            $html .= "
            <form method='POST'>
                <input type='hidden' name='pedidoId' value='$pedidoId'>
                <button type='submit' name='asignar'>
                    Quedarme este pedido
                </button>
            </form>
            ";
        }

        /* Líneas */

        $lineas = Cocina::getLineasPedido($pedidoId);

        if ($lineas) {

            $html .= "<ul>";

            foreach ($lineas as $linea) {

                $lineaId = $linea['id'];
                $nombre = $linea['nombre'];
                $cantidad = $linea['cantidad'];
                $preparado = $linea['preparado'];

                $html .= "<li>";

                $html .= "$nombre x $cantidad ";

                if (!$preparado) {

                    $html .= "
                    <form method='POST' style='display:inline'>
                        <input type='hidden' name='lineaId' value='$lineaId'>
                        <button type='submit' name='preparar'>
                            Preparado
                        </button>
                    </form>
                    ";

                } else {

                    $html .= "(Preparado)";
                }

                $html .= "</li>";
            }

            $html .= "</ul>";
        }

        /* Finalizar */

        if ($estado == 'cocinando') {

            $html .= "
            <form method='POST'>
                <input type='hidden' name='pedidoId' value='$pedidoId'>
                <button type='submit' name='finalizar'>
                    Finalizar pedido
                </button>
            </form>
            ";
        }
    }

    return $html;
}

    protected function procesaFormulario(&$datos)
    {
        if (isset($datos['asignar'])) {

            $pedidoId = $datos['pedidoId'];
            $cocineroId = $_SESSION['idUsuario'];

            Cocina::asignarPedido($pedidoId, $cocineroId);
        }

        if (isset($datos['preparar'])) {

            $lineaId = $datos['lineaId'];

            Cocina::prepararProducto($lineaId);
        }

        if (isset($datos['finalizar'])) {

            $pedidoId = $datos['pedidoId'];

            Cocina::finalizarPedido($pedidoId);
        }
    }
}