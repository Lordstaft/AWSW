<?php
namespace es\ucm\fdi\aw\ofertas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Oferta {

    use MagicProperties;

    private $idOferta;
    private $nombre;
    private $descripcion;
    private $fechaInicio;
    private $fechaFin;
    private $descuento;
    private $activa;

    public function __construct($idOferta, $nombre, $descripcion, $fechaInicio, $fechaFin, $descuento, $activa = 1) {
        $this->idOferta = $idOferta;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->descuento = $descuento;
        $this->activa = $activa ?? 1;
    }

    public function getIdOferta() {
        return $this->idOferta;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function getActiva() {
        return $this->activa;
    }

    public static function crearOferta($nombre, $descripcion, $fechaInicio, $fechaFin, $descuento) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO ofertas (nombre, descripcion, fechaInicio, fechaFin, descuento, activa)
             VALUES ('%s', '%s', '%s', '%s', %f, %d)",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($fechaInicio),
            $conn->real_escape_string($fechaFin),
            (float)$descuento,
            1
        );

        if ($conn->query($query)) {
            $idOferta = $conn->insert_id;
            return self::buscaOferta($idOferta);
        }

        return false;
    }

    public static function crearOfertaConProductos($nombre, $descripcion, $fechaInicio, $fechaFin, $descuento, $productos) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO ofertas (nombre, descripcion, fechaInicio, fechaFin, descuento, activa)
             VALUES ('%s', '%s', '%s', '%s', %f, %d)",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($fechaInicio),
            $conn->real_escape_string($fechaFin),
            (float)$descuento,
            1
        );

        if (!$conn->query($query)) {
            return false;
        }

        $idOferta = (int)$conn->insert_id;

        foreach ($productos as $productoId => $cantidad) {
            $productoId = (int)$productoId;
            $cantidad = (int)$cantidad;

            if ($productoId > 0 && $cantidad > 0) {
                self::añadirProductoOferta($idOferta, $productoId, $cantidad);
            }
        }

        return self::buscaOferta($idOferta);
    }

    public static function eliminarOferta($idOferta) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "DELETE FROM ofertas WHERE id = %d",
            (int)$idOferta
        );

        return $conn->query($query);
    }

    public static function añadirProductoOferta($idOferta, $productoId, $cantidad) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "INSERT INTO oferta_productos (oferta_id, producto_id, cantidad)
             VALUES (%d, %d, %d)",
            (int)$idOferta,
            (int)$productoId,
            (int)$cantidad
        );

        if ($conn->query($query)) {
            return true;
        }

        return false;
    }

    public static function actualizarOferta($idOferta, $nombre, $descripcion, $fechaInicio, $fechaFin, $descuento, $activa = 1) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE ofertas
             SET nombre = '%s',
                 descripcion = '%s',
                 fechaInicio = '%s',
                 fechaFin = '%s',
                 descuento = %f,
                 activa = %d
             WHERE id = %d",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($fechaInicio),
            $conn->real_escape_string($fechaFin),
            (float)$descuento,
            (int)$activa,
            (int)$idOferta
        );

        return $conn->query($query);
    }

    public static function actualizarOfertaConProductos($idOferta, $nombre, $descripcion, $fechaInicio, $fechaFin, $descuento, $productos, $activa = 1) {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "UPDATE ofertas
             SET nombre = '%s',
                 descripcion = '%s',
                 fechaInicio = '%s',
                 fechaFin = '%s',
                 descuento = %f,
                 activa = %d
             WHERE id = %d",
            $conn->real_escape_string($nombre),
            $conn->real_escape_string($descripcion),
            $conn->real_escape_string($fechaInicio),
            $conn->real_escape_string($fechaFin),
            (float)$descuento,
            (int)$activa,
            (int)$idOferta
        );

        if (!$conn->query($query)) {
            return false;
        }

        $queryDelete = sprintf(
            "DELETE FROM oferta_productos WHERE oferta_id = %d",
            (int)$idOferta
        );

        $conn->query($queryDelete);

        foreach ($productos as $productoId => $cantidad) {
            $productoId = (int)$productoId;
            $cantidad = (int)$cantidad;

            if ($productoId > 0 && $cantidad > 0) {
                self::añadirProductoOferta($idOferta, $productoId, $cantidad);
            }
        }

        return true;
    }

    public static function buscaOferta($idOferta) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT * FROM ofertas WHERE id = %d",
            (int)$idOferta
        );

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();

            $oferta = new Oferta(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['descuento'],
                $fila['activa']
            );

            $rs->free();
            return $oferta;
        }

        return null;
    }

    public static function listarOfertas() {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM ofertas ORDER BY fechaInicio DESC, id DESC";

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $ofertas = [];

            while ($fila = $rs->fetch_assoc()) {
                $ofertas[] = new Oferta(
                    $fila['id'],
                    $fila['nombre'],
                    $fila['descripcion'],
                    $fila['fechaInicio'],
                    $fila['fechaFin'],
                    $fila['descuento'],
                    $fila['activa']
                );
            }

            $rs->free();
            return $ofertas;
        }

        return null;
    }

    public static function ofertasDisponibles() {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = "SELECT * FROM ofertas
                  WHERE activa = 1
                  AND CURDATE() BETWEEN fechaInicio AND fechaFin
                  ORDER BY fechaInicio DESC, id DESC";

        $rs = $conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $ofertas = [];

            while ($fila = $rs->fetch_assoc()) {
                $ofertas[] = new Oferta(
                    $fila['id'],
                    $fila['nombre'],
                    $fila['descripcion'],
                    $fila['fechaInicio'],
                    $fila['fechaFin'],
                    $fila['descuento'],
                    $fila['activa']
                );
            }

            $rs->free();
            return $ofertas;
        }

        return null;
    }

    public static function productosOferta($idOferta) {

        $conn = Aplicacion::getInstance()->getConexionBd();

        $query = sprintf(
            "SELECT op.producto_id, op.cantidad, p.nombreProd, p.precio, p.iva
             FROM oferta_productos op
             JOIN productos p ON p.id = op.producto_id
             WHERE op.oferta_id = %d
             ORDER BY p.nombreProd ASC",
            (int)$idOferta
        );

        $rs = $conn->query($query);

        $productos = [];

        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $productos[] = $fila;
            }

            $rs->free();
        }

        return $productos;
    }

    public function getProductos() {
        return self::productosOferta($this->idOferta);
    }

    public function precioPackConIva() {
        $productos = self::productosOferta($this->idOferta);
        $total = 0;

        foreach ($productos as $producto) {
            $precio = (float)$producto['precio'];
            $iva = (int)$producto['iva'];
            $cantidad = (int)$producto['cantidad'];

            $precioConIva = $precio * (1 + ($iva / 100));
            $total += $precioConIva * $cantidad;
        }

        return round($total, 2);
    }

    public function ahorroOferta() {
        $precioBase = $this->precioPackConIva();
        $descuento = (float)$this->descuento;

        return round($precioBase * ($descuento / 100), 2);
    }

    public function precioFinalOferta() {
        return round($this->precioPackConIva() - $this->ahorroOferta(), 2);
    }

    public function estaDisponible() {
        $hoy = date('Y-m-d');

        return $this->activa
            && $hoy >= $this->fechaInicio
            && $hoy <= $this->fechaFin;
    }
}