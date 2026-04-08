<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\usuarios\EstadosPedido;

use es\ucm\fdi\aw\MagicProperties;

class Pedido {

    use MagicProperties;

    private $idPedido;
    private $usuario_id;
    private $estadoPedido;
    private $fechaPedido;
    private $tipo;
    private $total;
    private $cocinero_id;

    public function __construct($idPedido, $usuario_id, $estadoPedido, $fechaPedido, $tipo, $total, $cocinero_id) {
        $this->idPedido = $idPedido;
        $this->usuario_id = $usuario_id;
        $this->estadoPedido = $estadoPedido ?? EstadoPedido::PENDIENTE->value;
        $this->fechaPedido = $fechaPedido;
        $this->tipo = $tipo;
        $this->total = $total;
        $this->cocinero_id = $cocinero_id ?? null;
    }

    public function getFechaPedido() {
        return $this->fechaPedido;
    }

    public function getCocineroId() {
        return $this->cocinero_id;
    }

    public function getPedidoId() {
        return $this->idPedido;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getEstadoPedido() {
        return $this->estadoPedido;
    }
    

    public static function crearPedido($usuarioId, $tipo, $carrito) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $total = 0;

        foreach ($carrito as $id => $cantidad) {

            $producto = Producto::buscaPorId($id);

            if ($producto) {
                $total += $producto['precio'] * $cantidad;
            }
        }
    }

    public static function pedidosPendientes() {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("SELECT * FROM pedidos WHERE estadoPedido = '%s'", $conn->real_escape_string(EstadoPedido::PENDIENTE->value));

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['idPedido'],
                    $fila['usuario_id'],
                    $fila['estadoPedido'],
                    $fila['fechaPedido'],
                    $fila['tipo'],
                    $fila['total'],
                    $fila['cocinero_id']
                );
            }
            $rs->free();
            return $pedidos;
        }

        return null;
    }

    public static function asignarPedido($idPedido, $cocineroId) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("UPDATE pedidos SET estadoPedido = '%s', cocinero_id = %d WHERE idPedido = '%d'", 
            $conn->real_escape_string(EstadoPedido::EN_PREPARACION->value),
            (int)$cocineroId,
            (int)$idPedido
        );

        return $conn->query($query);
    }

    public static function pedidosPendientesCocinero($cocineroId) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM pedidos WHERE estadoPedido != '%s' AND estadoPedido != '%s' AND estadoPedido != '%s' AND estadoPedido != '%s' AND cocinero_id = %d",
            $conn->real_escape_string(EstadoPedido::CANCELADO->value),
            $conn->real_escape_string(EstadoPedido::ENTREGADO->value),
            $conn->real_escape_string(EstadoPedido::LISTO->value),
            $conn->real_escape_string(EstadoPedido::RECIBIDO->value),
            (int)$cocineroId
        );

        $rs = $conn->query($query);

        $pedidos = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['idPedido'],
                    null,
                    $fila['estadoPedido'],
                    $fila['fechaPedido'],
                    $fila['tipo'],
                    null,
                    $fila['cocinero_id']
                );
            }
            $rs->free();
        }

        return $pedidos;
    }

    public static function buscaPedido($idPedido) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("SELECT * FROM pedidos WHERE idPedido = %d", (int)$idPedido);

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $pedido = new Pedido(
                $fila['idPedido'],
                null,
                $fila['estadoPedido'],
                $fila['fechaPedido'],
                $fila['tipo'],
                null,
                $fila['cocinero_id']
            );

            $rs->free();
            return $pedido;
        }

        return null;
    }

    public static function pedidosPendientes_Asignados($esAdmin) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        if ($esAdmin) {
            $query = sprintf("SELECT * FROM pedidos WHERE estadoPedido != '%s'", 
                $conn->real_escape_string(EstadoPedido::ENTREGADO->value)
            );
        }

        else {
            $query = sprintf("SELECT * FROM pedidos WHERE estadoPedido = '%s' AND estadoPedido = '%s'", 
                $conn->real_escape_string(EstadoPedido::PENDIENTE->value),
                $conn->real_escape_string(EstadoPedido::EN_PREPARACION->value)
            );
        }

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['idPedido'],
                    null,
                    $fila['estadoPedido'],
                    $fila['fechaPedido'],
                    $fila['tipo'],
                    null,
                    $fila['cocinero_id']
                );
            }
            $rs->free();
            return $pedidos;
        }

        return null;
    }

    public static function pedidosListosEntrega(){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("SELECT * FROM pedidos WHERE estadoPedido = '%s'", 
            $conn->real_escape_string(EstadoPedido::LISTO->value)
        );

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['idPedido'],
                    null,
                    $fila['estadoPedido'],
                    $fila['fechaPedido'],
                    $fila['tipo'],
                    null,
                    $fila['cocinero_id']
                );
            }
            $rs->free();
            return $pedidos;
        }

        return null;
    }

    public static function modificarAsignacion($idPedido, $idCocinero, $estadoPedido) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        if($estadoPedido === EstadoPedido::PENDIENTE->value){
            $query = sprintf("UPDATE pedidos SET cocinero_id = null, estadoPedido = '%s' WHERE idPedido = %d",
                $conn->real_escape_string($estadoPedido),
                (int)$idPedido
            );
        }

        else{
            $query = sprintf("UPDATE pedidos SET cocinero_id = %d, estadoPedido = '%s' WHERE idPedido = %d",
                (int)$idCocinero,
                $conn->real_escape_string($estadoPedido),
                (int)$idPedido
            );
        }

        return $conn->query($query);
    }

    public static function realizarEntrega($idPedido){
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("UPDATE pedidos SET estadoPedido = '%s' WHERE idPedido = %d",
            $conn->real_escape_string(EstadoPedido::ENTREGADO->value),
            (int)$idPedido
        );

        return $conn->query($query);
    }

    public static function buscaProductos($idPedido) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("SELECT p.* FROM productos p JOIN pedido_productos pp ON p.id = pp.producto_id  WHERE pp.pedido_id = %d",
            (int)$idPedido
        );

        $rs = $conn->query($query);

        $productos = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {

                $productos[] = new Producto(
                    $fila['id'],
                    $fila['nombreProd'],
                    $fila['descripcion'],
                    $fila['categoria_id'],
                    $fila['precio'],
                    $fila['iva'],
                    $fila['stock'],
                    $fila['disponible'],
                    $fila['ofertado'],
                    $fila['fechaCreacion']
                );
            }

            $rs->free();
        }

        return $productos;
    }

}
