<?php
namespace es\ucm\fdi\aw\pedidos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\MagicProperties;

class Pedido {

    use MagicProperties;

    // Estados posibles según la BD:
    // 'nuevo', 'recibido', 'en_preparacion', 'cocinando', 'listo_cocina', 'terminado', 'entregado', 'cancelado'

    private $id;
    private $usuario_id;
    private $estado;
    private $fechaPedido;
    private $tipo;
    private $total;
    private $cocinero_id;

    public function __construct($id, $usuario_id, $estado, $fechaPedido, $tipo, $total, $cocinero_id) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->estado = $estado ?? 'nuevo';
        $this->fechaPedido = $fechaPedido;
        $this->tipo = $tipo;
        $this->total = $total ?? 0;
        $this->cocinero_id = $cocinero_id ?? null;
    }

    public function getFechaPedido() {
        return $this->fechaPedido;
    }

    public function getCocineroId() {
        return $this->cocinero_id;
    }

    public function getPedidoId() {
        return $this->id;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getEstadoPedido() {
        return $this->estado;
    }

    public static function crearPedido($usuarioId, $tipo, $estado, $subtotalSinDescuento, $descuentoAplicado, $total)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        if (empty($estado)) {
            $estado = 'nuevo';
        }

        $query = sprintf(
            "INSERT INTO pedidos (usuario_id, estado, tipo, subtotalSinDescuento, descuentoAplicado, total)
            VALUES (%d, '%s', '%s', %f, %f, %f)",
            (int) $usuarioId,
            $conn->real_escape_string($estado),
            $conn->real_escape_string($tipo),
            (float) $subtotalSinDescuento,
            (float) $descuentoAplicado,
            (float) $total
        );

        if ($conn->query($query)) {
            return $conn->insert_id;
        }

        return false;
    }

    public static function eliminarPedido($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $conn->query(sprintf("DELETE FROM pedido_productos WHERE pedido_id = %d", (int)$id));
        $conn->query(sprintf("DELETE FROM pedidos WHERE id = %d", (int)$id));
    }

    public static function añadirProductoPedido($pedidoId, $productoId, $cantidad, $precio, $iva) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO pedido_productos (pedido_id, producto_id, cantidad, precioUnitario, ivaAplicado)
            VALUES (%d, %d, %d, %f, '%s')",
            (int)$pedidoId,
            (int)$productoId,
            (int)$cantidad,
            (float)$precio,
            (string)$iva
        );

        if ($conn->query($query)) {
            return true;
        }

        return false;
    }

    public static function pedidosPendientes() {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM pedidos WHERE estado IN ('%s', '%s')",
            $conn->real_escape_string(EstadoPedido::PENDIENTE->value),
            $conn->real_escape_string(EstadoPedido::NUEVO->value)
        );

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];

            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['id'],
                    $fila['usuario_id'],
                    $fila['estado'],
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

    public static function asignarPedido($id, $cocineroId) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedidos SET estado = 'preparando', cocinero_id = %d WHERE id = %d",
            (int)$cocineroId,
            (int)$id
        );

        return $conn->query($query);
    }

    public static function pedidosPendientesCocinero($cocineroId) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM pedidos
             WHERE estado NOT IN ('cancelado', 'entregado', 'listo', 'recibido')
             AND cocinero_id = %d",
            (int)$cocineroId
        );

        $rs = $conn->query($query);

        $pedidos = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['id'],
                    null,
                    $fila['estado'],
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

    public static function actualizarPrecioPedido($id, $total) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedidos SET total = %f WHERE id = %d",
            (float)$total,
            (int)$id
        );

        $conn->query($query);
    }

    public static function buscaPedido($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf("SELECT * FROM pedidos WHERE id = %d", (int)$id);

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $pedido = new Pedido(
                $fila['id'],
                null,
                $fila['estado'],
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
            $query = sprintf(
                "SELECT * FROM pedidos WHERE estado != '%s'",
                $conn->real_escape_string(EstadoPedido::ENTREGADO->value)
            );
        } else {
            $query = sprintf(
                "SELECT * FROM pedidos WHERE estado IN ('%s','%s')",
                $conn->real_escape_string(EstadoPedido::PENDIENTE->value),
                $conn->real_escape_string(EstadoPedido::EN_PREPARACION->value)
            );
        }

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];

            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['id'],
                    null,
                    $fila['estado'],
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


    public static function pedidosListosEntrega() {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM pedidos WHERE estado = 'listo'";

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['id'],
                    null,
                    $fila['estado'],
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

    public static function modificarAsignacion($id, $idCocinero, $estado) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        if ($estado === EstadoPedido::PENDIENTE->value || $estado === EstadoPedido::NUEVO->value) {
            $query = sprintf(
                "UPDATE pedidos SET cocinero_id = NULL, estado = '%s' WHERE id = %d",
                $conn->real_escape_string($estado),
                (int)$id
            );
        } else {
            $query = sprintf(
                "UPDATE pedidos SET cocinero_id = %d, estado = '%s' WHERE id = %d",
                (int)$idCocinero,
                $conn->real_escape_string($estado),
                (int)$id
            );
        }

        return $conn->query($query);
    }


    public static function marcarPlatoPreparado($pedidoId, $productoId) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        // INSERT IGNORE garantiza que existe la fila aunque no se inicializó antes
        $conn->query(sprintf(
            "INSERT IGNORE INTO pedido_producto_estado (pedido_id, producto_id, preparado) VALUES (%d, %d, 1)",
            (int)$pedidoId,
            (int)$productoId
        ));

        return $conn->query(sprintf(
            "UPDATE pedido_producto_estado SET preparado = 1 WHERE pedido_id = %d AND producto_id = %d",
            (int)$pedidoId,
            (int)$productoId
        ));
    }

    public static function realizarEntrega($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE pedidos SET estado = 'entregado' WHERE id = %d",
            (int)$id
        );

        return $conn->query($query);
    }

    public static function pedidosUsuario($usuario_id, $tipo) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        if ($tipo) {
            $query = sprintf(
                "SELECT * FROM pedidos WHERE usuario_id = %d AND estado NOT IN ('entregado', 'cancelado')",
                (int)$usuario_id
            );
        } else {
            $query = sprintf(
                "SELECT * FROM pedidos WHERE usuario_id = %d AND estado IN ('entregado', 'cancelado')",
                (int)$usuario_id
            );
        }

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $pedidos = [];
            while ($fila = $rs->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $fila['id'],
                    null,
                    $fila['estado'],
                    $fila['fechaPedido'],
                    $fila['tipo'],
                    $fila['total'],
                    null
                );
            }
            $rs->free();
            return $pedidos;
        }

        return null;
    }

    public static function buscaProductosCocina($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT p.*, pp.cantidad, 
                    COALESCE(ppe.preparado, 0) AS preparado
            FROM productos p
            JOIN pedido_productos pp ON p.id = pp.producto_id
            LEFT JOIN pedido_producto_estado ppe 
                ON p.id = ppe.producto_id AND pp.pedido_id = ppe.pedido_id
            WHERE pp.pedido_id = %d",
            (int)$id
        );

        $rs = $conn->query($query);

        $resultado = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {

                $producto = new Producto(
                    $fila['id'],
                    $fila['nombreProd'],
                    $fila['descripcion'],
                    $fila['categoria_id'],
                    $fila['precio'],
                    $fila['iva'],
                    $fila['stock'],
                    $fila['disponible'],
                    $fila['ofertado'],
                    $fila['rutaImg'],
                    $fila['fechaCreacion']
                );

                $resultado[] = [
                    'producto'  => $producto,
                    'cantidad'  => $fila['cantidad'],
                    'preparado' => (int)$fila['preparado']
                ];
            }
            $rs->free();
        }

        return $resultado;
    }

    public static function buscaProductos($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT p.* FROM productos p
             JOIN pedido_productos pp ON p.id = pp.producto_id
             WHERE pp.pedido_id = %d",
            (int)$id
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

    // Función necesaria para que funcione la funcionalidad 4: gestión de ofertas
    public static function insertarOfertaPedido($pedidoId, $ofertaId, $vecesAplicada, $descuentoTotal)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO pedido_ofertas (pedido_id, oferta_id, vecesAplicada, descuentoTotal)
            VALUES (%d, %d, %d, %f)",
            (int) $pedidoId,
            (int) $ofertaId,
            (int) $vecesAplicada,
            (float) $descuentoTotal
        );

        return $conn->query($query);
    }

}
