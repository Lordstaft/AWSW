<?php
namespace es\ucm\fdi\aw\productos;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Producto {
    use MagicProperties;
    private $id;
    private $nombreProd;
    private $descripcion;
    private $categoria_id;
    private $precio;
    private $iva;
    private $stock;
    private $disponible;
    private $ofertado;
    private $fechaCreacion;
    private $rutaImagen;
    public function __construct($id, $nombreProd, $descripcion, $categoria_id, $precio, $iva, $stock, $disponible, $ofertado, $fechaCreacion, $rutaImagen = null) {
        $this->id = $id;
        $this->nombreProd = $nombreProd;
        $this->descripcion = $descripcion;
        $this->categoria_id = $categoria_id;
        $this->precio = $precio;
        $this->iva = $iva;
        $this->stock = $stock ?? 0;
        $this->disponible = $disponible ?? 1;
        $this->ofertado = $ofertado ?? 0;
        $this->fechaCreacion = $fechaCreacion ?? '';
        $this->rutaImagen = $rutaImagen ?? 'producto_default.jpg';
    }
    public function getId() {
        return $this->id;
    }
    public function getNombreProd() {
        return $this->nombreProd;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function getRutaImagen(){
        return $this->rutaImagen;
    }
    public function getCategoriaId() {
        return $this->categoria_id;
    }
    public function getPrecio() {
        return $this->precio;
    }
    public function getIva() {
        return $this->iva;
    }
    public function getStock() {
        return $this->stock;
    }
    public function getDisponible() {
        return $this->disponible;
    }
    public function getOfertado() {
        return $this->ofertado;
    }
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }
    public static function listar($categoria) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        if($categoria === 'Todos' || $categoria === '') {
            $sql = sprintf("SELECT p.*, pi.rutaImagen
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                JOIN producto_imagenes pi ON p.id = pi.producto_id
                GROUP BY p.id
                ORDER BY c.nombre
                ");
        }
        else{
            $sql = sprintf("SELECT p.*, pi.rutaImagen
                FROM productos p
                JOIN producto_imagenes pi ON p.id = pi.producto_id
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE categoria_id = %d
                GROUP BY p.id
                ORDER BY p.nombreProd",
                (int)$categoria);
        }
        $rs = $conn->query($sql);
        $productos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $productos[] = new Producto(
                    $fila['id'],
                    $fila['nombreProd'],
                    $fila['descripcion'],
                    $fila['categoria'] ?? null,
                    $fila['precio'],
                    $fila['iva'],
                    $fila['stock'],
                    $fila['disponible'],
                    $fila['ofertado'],
                    null,
                    $fila['rutaImagen']
                );
            }
            $rs->free();
        }
        return $productos;
    }
    public static function buscaPorNombre($nombreProd) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "SELECT * FROM productos p JOIN producto_imagenes pi ON p.id = pi.producto_id WHERE p.nombreProd = '%s'",
            $conn->real_escape_string($nombreProd)
        );
        $rs = $conn->query($query);
        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
            $producto = new Producto(
                $fila['id'],
                $fila['nombreProd'],
                $fila['descripcion'],
                $fila['categoria_id'],
                $fila['precio'],
                $fila['iva'],
                null,
                $fila['disponible'],
                $fila['ofertado'],
                null,
                $fila['rutaImagen']
            );
            $rs->free();
            return $producto;
        }
        return null;
    }
    public static function creaProducto($nombreProd, $descripcion, $categoria_id, $precio, $iva, $stock, $disponible, $ofertado, $imagen = 'producto_default.jpg') {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO productos (nombreProd, descripcion, categoria_id, precio, iva, stock, disponible, ofertado)
             VALUES ('%s', '%s', %d, %.2f, '%s', %d, %d, %d)",
            $conn->real_escape_string($nombreProd),
            $conn->real_escape_string($descripcion),
            (int)$categoria_id,
            (float)$precio,
            $conn->real_escape_string($iva),
            (int)$stock,
            (int)$disponible,
            (int)$ofertado
        );
        if ($conn->query($query)) {
            $idProducto = $conn->insert_id;
            $conn->query(sprintf(
                "INSERT INTO producto_imagenes (producto_id, rutaImagen)
                VALUES (%d, '%s')",
                $idProducto,
                $conn->real_escape_string($imagen)
            ));
            return true;
        }
    }
    public static function buscaPorId($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "SELECT p.*, pi.rutaImagen FROM productos p JOIN producto_imagenes pi ON p.id = pi.producto_id
            WHERE p.id = %d GROUP BY p.id",
            (int)$id
        );
        $rs = $conn->query($query);
        if ($rs && $rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
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
                $fila['fechaCreacion'],
                $fila['rutaImagen']
            );
            $rs->free();
            return $producto;
        }
        return null;
    }
    public static function listarProductosCliente(){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("SELECT c.nombre, p.*, pi.rutaImagen FROM categorias c 
        JOIN productos p ON c.id = p.categoria_id JOIN producto_imagenes pi ON p.id = pi.producto_id 
        WHERE p.disponible = 1 AND p.stock > 0 GROUP BY p.id ORDER BY c.nombre");
        $rs = $conn->query($sql);
        $productos = [];
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $productos[] = new Producto(
                    $fila['id'],
                    $fila['nombreProd'],
                    $fila['descripcion'],
                    $fila['categoria'] ?? null,
                    $fila['precio'],
                    $fila['iva'],
                    null,
                    $fila['disponible'],
                    $fila['ofertado'],
                    null,
                    $fila['rutaImagen']
                );
            }
            $rs->free();
            return $productos;
        }
        return null;
    }
    public static function actualiza($id, $nombreProd, $descripcion, $categoria_id, $precio, $iva, $stock, $disponible, $ofertado, $imagen)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $conn->query(sprintf(
            "DELETE FROM producto_imagenes WHERE producto_id = %d",
            (int)$id
        ));
        $conn->query(sprintf(
            "INSERT INTO producto_imagenes (producto_id, rutaImagen)
            VALUES (%d, '%s')",
            (int)$id,
            $conn->real_escape_string($imagen)
        ));
        $query = sprintf(
            "UPDATE productos
            SET nombreProd = '%s',
                descripcion = '%s',
                categoria_id = %d,
                precio = %.2f,
                iva = '%s',
                disponible = %d,
                ofertado = %d,
                stock = %d
            WHERE id = %d",
            $conn->real_escape_string($nombreProd),
            $conn->real_escape_string($descripcion),
            (int)$categoria_id,
            (float)$precio,
            $conn->real_escape_string($iva),
            (int)$disponible,
            (int)$ofertado,
            (int)$stock,
            (int)$id
        );
        return $conn->query($query);
    }
    public static function borra($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM productos WHERE id = %d",
            (int)$id
        );
        return $conn->query($query);
    }
    public static function retirar($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "UPDATE productos SET disponible = 0 WHERE id = %d",
            (int)$id
        );
        return $conn->query($query);
    }
}