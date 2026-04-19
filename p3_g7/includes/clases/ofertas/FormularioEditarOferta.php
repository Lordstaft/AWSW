<?php
namespace es\ucm\fdi\aw\ofertas;
 
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Producto;
 
class FormularioEditarOferta extends Formulario
{
    private $idOferta;
    private $oferta;
 
    public function __construct($idOferta)
    {
        parent::__construct('formEditarOferta');
        $this->idOferta = (int)$idOferta;
        $this->oferta = Oferta::buscaOferta($this->idOferta);
    }
 
    protected function generaCamposFormulario(&$datos)
    {
        if (!$this->oferta) {
            return '<p>La oferta no existe.</p>';
        }
 
        $nombre = htmlspecialchars($datos['nombre'] ?? $this->oferta->getNombre());
        $descripcion = htmlspecialchars($datos['descripcion'] ?? $this->oferta->getDescripcion());
        $fechaInicio = htmlspecialchars($datos['fechaInicio'] ?? $this->oferta->getFechaInicio());
        $fechaFin = htmlspecialchars($datos['fechaFin'] ?? $this->oferta->getFechaFin());
        $descuento = htmlspecialchars($datos['descuento'] ?? $this->oferta->getDescuento());
        $precioFinal = htmlspecialchars($datos['precioFinal'] ?? $this->oferta->precioFinalOferta());
 
        $productosOferta = $this->oferta->getProductos();
        $cantidadesActuales = [];
 
        foreach ($productosOferta as $productoOferta) {
            $cantidadesActuales[(int)$productoOferta['producto_id']] = (int)$productoOferta['cantidad'];
        }
 
        if (isset($datos['cantidades']) && is_array($datos['cantidades'])) {
            foreach ($datos['cantidades'] as $idProducto => $cantidad) {
                $cantidadesActuales[(int)$idProducto] = (int)$cantidad;
            }
        }
 
        $productos = Producto::listar('Todos');
        $filasProductos = '';
 
        if ($productos) {
            foreach ($productos as $producto) {
                $idProducto = (int)$producto->getId();
                $nombreProd = htmlspecialchars($producto->getNombreProd());
                $precio = number_format((float)$producto->getPrecio(), 2, '.', '');
                $iva = (int)$producto->getIva();
                $cantidad = $cantidadesActuales[$idProducto] ?? 0;
 
                $filasProductos .= <<<EOS
                <tr>
                    <td>{$nombreProd}</td>
                    <td>{$precio}</td>
                    <td>{$iva}</td>
                    <td>
                        <input type="number" name="cantidades[$idProducto]" class="cantidad-prod" data-precio="{$precio}" data-iva="{$iva}" min="0" value="{$cantidad}">
                    </td>
                </tr>
EOS;
            }
        }
 
        return <<<EOS
        <fieldset>
            <legend>Editar oferta</legend>
 
            <p>
                <label for="nombre">Nombre:</label><br>
                <input type="text" name="nombre" id="nombre" value="{$nombre}" required>
            </p>
 
            <p>
                <label for="descripcion">Descripción:</label><br>
                <textarea name="descripcion" id="descripcion" rows="4" cols="50">{$descripcion}</textarea>
            </p>
 
            <p>
                <label for="fechaInicio">Fecha de inicio:</label><br>
                <input type="date" name="fechaInicio" id="fechaInicio" value="{$fechaInicio}" required>
            </p>
 
            <p>
                <label for="fechaFin">Fecha de fin:</label><br>
                <input type="date" name="fechaFin" id="fechaFin" value="{$fechaFin}" required>
            </p>
 
            <h3>Productos incluidos en la oferta</h3>
 
            <table class="tabla-ofertas">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio base (€)</th>
                        <th>IVA (%)</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    $filasProductos
                </tbody>
            </table>
 
            <h3>Resumen del pack</h3>
 
            <p>
                <label for="precioPack">Precio del pack con IVA:</label><br>
                <input type="number" step="0.01" id="precioPack" name="precioPack" value="0.00" readonly>
            </p>
 
            <p>
                <label for="precioFinal">Precio final de la oferta:</label><br>
                <input type="number" step="0.01" min="0" name="precioFinal" id="precioFinal" value="{$precioFinal}">
            </p>
 
            <p>
                <label for="descuento">Descuento (%):</label><br>
                <input type="number" step="0.01" min="0" name="descuento" id="descuento" value="{$descuento}" readonly>
            </p>
 
            <p>
                <button type="submit">Guardar cambios</button>
            </p>
        </fieldset>
 
        <script>
            (function () {
                var cantidades = document.querySelectorAll('.cantidad-prod');
                var precioPackInput = document.getElementById('precioPack');
                var precioFinalInput = document.getElementById('precioFinal');
                var descuentoInput = document.getElementById('descuento');
 
                function redondear(num) {
                    return Math.round(num * 100) / 100;
                }
 
                function calcularPrecioPack() {
                    var total = 0;
 
                    for (var i = 0; i < cantidades.length; i++) {
                        var input = cantidades[i];
                        var cantidad = parseInt(input.value) || 0;
                        var precio = parseFloat(input.getAttribute('data-precio')) || 0;
                        var iva = parseFloat(input.getAttribute('data-iva')) || 0;
 
                        var precioConIva = precio + (precio * iva / 100);
                        total += cantidad * precioConIva;
                    }
 
                    total = redondear(total);
                    precioPackInput.value = total.toFixed(2);
                    calcularDescuento();
                }
 
                function calcularDescuento() {
                    var precioPack = parseFloat(precioPackInput.value) || 0;
                    var precioFinal = parseFloat(precioFinalInput.value) || 0;
 
                    if (precioPack <= 0) {
                        descuentoInput.value = '0.00';
                        return;
                    }
 
                    var descuento = ((precioPack - precioFinal) / precioPack) * 100;
 
                    if (descuento < 0) {
                        descuento = 0;
                    }
 
                    descuento = redondear(descuento);
                    descuentoInput.value = descuento.toFixed(2);
                }
 
                for (var i = 0; i < cantidades.length; i++) {
                    cantidades[i].addEventListener('input', calcularPrecioPack);
                }
 
                precioFinalInput.addEventListener('input', calcularDescuento);
 
                calcularPrecioPack();
            })();
        </script>
EOS;
    }
 
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
 
        if (!$this->oferta) {
            $this->errores[] = 'La oferta no existe.';
            return;
        }
 
        $nombre = trim($datos['nombre'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $fechaInicio = trim($datos['fechaInicio'] ?? '');
        $fechaFin = trim($datos['fechaFin'] ?? '');
        $precioFinal = trim($datos['precioFinal'] ?? '');
        $cantidades = $datos['cantidades'] ?? [];
 
        if ($nombre === '') {
            $this->errores[] = 'El nombre de la oferta es obligatorio.';
        }
 
        if ($fechaInicio === '') {
            $this->errores[] = 'La fecha de inicio es obligatoria.';
        }
 
        if ($fechaFin === '') {
            $this->errores[] = 'La fecha de fin es obligatoria.';
        }
 
        if ($fechaInicio !== '' && $fechaFin !== '' && $fechaInicio > $fechaFin) {
            $this->errores[] = 'La fecha de inicio no puede ser posterior a la fecha de fin.';
        }
 
        if ($precioFinal === '' || !is_numeric($precioFinal) || (float)$precioFinal < 0) {
            $this->errores[] = 'El precio final debe ser un número válido.';
        }
 
        $productos = Producto::listar('Todos');
        $mapaProductos = [];
 
        if ($productos) {
            foreach ($productos as $producto) {
                $mapaProductos[(int)$producto->getId()] = $producto;
            }
        }
 
        $productosOferta = [];
        $precioPack = 0.0;
 
        if (is_array($cantidades)) {
            foreach ($cantidades as $productoId => $cantidad) {
                $productoId = (int)$productoId;
                $cantidad = (int)$cantidad;
 
                if ($productoId > 0 && $cantidad > 0 && isset($mapaProductos[$productoId])) {
                    $productosOferta[$productoId] = $cantidad;
 
                    $producto = $mapaProductos[$productoId];
                    $precio = (float)$producto->getPrecio();
                    $iva = (int)$producto->getIva();
                    $precioConIva = $precio + ($precio * $iva / 100);
 
                    $precioPack += $precioConIva * $cantidad;
                }
            }
        }
 
        $precioPack = round($precioPack, 2);
 
        if (count($productosOferta) === 0) {
            $this->errores[] = 'Debes seleccionar al menos un producto con cantidad mayor que 0.';
        }
 
        if ($precioPack > 0 && (float)$precioFinal > $precioPack) {
            $this->errores[] = 'El precio final no puede ser mayor que el precio del pack.';
        }
 
        if (count($this->errores) > 0) {
            return;
        }
 
        $precioFinal = round((float)$precioFinal, 2);
        $descuentoCalculado = 0.0;
 
        if ($precioPack > 0) {
            $descuentoCalculado = (($precioPack - $precioFinal) / $precioPack) * 100;
            if ($descuentoCalculado < 0) {
                $descuentoCalculado = 0;
            }
        }
 
        $descuentoCalculado = round($descuentoCalculado, 2);
 
        $ok = Oferta::actualizarOfertaConProductos(
            $this->idOferta,
            $nombre,
            $descripcion,
            $fechaInicio,
            $fechaFin,
            $descuentoCalculado,
            $productosOferta,
            1
        );
 
        if (!$ok) {
            $this->errores[] = 'No se pudo actualizar la oferta.';
            return;
        }
 
        $app = Aplicacion::getInstance();
        header('Location: ' . $app->resuelve('/usuarios/gerente/ofertas.php'));
        exit();
    }
}