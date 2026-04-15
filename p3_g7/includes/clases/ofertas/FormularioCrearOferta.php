<?php
namespace es\ucm\fdi\aw\ofertas;

use es\ucm\fdi\aw\formularios\Formulario;
use es\ucm\fdi\aw\productos\Producto;

class FormularioCrearOferta extends Formulario
{
    public function __construct()
    {
        parent::__construct('formCrearOferta');
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombre = htmlspecialchars($datos['nombre'] ?? '');
        $descripcion = htmlspecialchars($datos['descripcion'] ?? '');
        $fechaInicio = htmlspecialchars($datos['fechaInicio'] ?? '');
        $fechaFin = htmlspecialchars($datos['fechaFin'] ?? '');
        $descuento = htmlspecialchars($datos['descuento'] ?? '');
        $precioFinal = htmlspecialchars($datos['precioFinal'] ?? '');

        $productos = Producto::listar();
        $filasProductos = '';

        if ($productos) {
            foreach ($productos as $producto) {
                $idProducto = (int)$producto['id'];
                $nombreProd = htmlspecialchars($producto['nombreProd']);
                $precio = number_format((float)$producto['precio'], 2, '.', '');
                $iva = (int)$producto['iva'];
                $cantidad = isset($datos['cantidades'][$idProducto]) ? (int)$datos['cantidades'][$idProducto] : 0;

                $filasProductos .= <<<EOS
                <tr>
                    <td>{$nombreProd}</td>
                    <td class="precio-base">{$precio}</td>
                    <td class="iva-prod">{$iva}</td>
                    <td>
                        <input type="number" name="cantidades[$idProducto]" class="cantidad-prod" data-precio="{$precio}" data-iva="{$iva}" min="0" value="{$cantidad}">
                    </td>
                </tr>
EOS;
            }
        }

        return <<<EOS
        <fieldset>
            <legend>Crear oferta</legend>

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

            <table border="1">
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
                <button type="submit">Crear oferta</button>
            </p>
        </fieldset>

        <script>
            (function () {
                const cantidades = document.querySelectorAll('.cantidad-prod');
                const precioPackInput = document.getElementById('precioPack');
                const precioFinalInput = document.getElementById('precioFinal');
                const descuentoInput = document.getElementById('descuento');

                function redondear(num) {
                    return Math.round(num * 100) / 100;
                }

                function calcularPrecioPack() {
                    let total = 0;

                    cantidades.forEach(input => {
                        const cantidad = parseInt(input.value) || 0;
                        const precio = parseFloat(input.dataset.precio) || 0;
                        const iva = parseFloat(input.dataset.iva) || 0;

                        const precioConIva = precio * (1 + iva / 100);
                        total += cantidad * precioConIva;
                    });

                    total = redondear(total);
                    precioPackInput.value = total.toFixed(2);

                    if (!precioFinalInput.value || parseFloat(precioFinalInput.value) === 0) {
                        precioFinalInput.value = total.toFixed(2);
                    }

                    calcularDescuento();
                }

                function calcularDescuento() {
                    const precioPack = parseFloat(precioPackInput.value) || 0;
                    const precioFinal = parseFloat(precioFinalInput.value) || 0;

                    if (precioPack <= 0) {
                        descuentoInput.value = '0.00';
                        return;
                    }

                    let descuento = ((precioPack - precioFinal) / precioPack) * 100;

                    if (descuento < 0) {
                        descuento = 0;
                    }

                    descuento = redondear(descuento);
                    descuentoInput.value = descuento.toFixed(2);
                }

                cantidades.forEach(input => {
                    input.addEventListener('input', calcularPrecioPack);
                });

                precioFinalInput.addEventListener('input', calcularDescuento);

                calcularPrecioPack();
            })();
        </script>
EOS;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $fechaInicio = trim($datos['fechaInicio'] ?? '');
        $fechaFin = trim($datos['fechaFin'] ?? '');
        $descuento = trim($datos['descuento'] ?? '');
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

        $productos = Producto::listar();
        $mapaProductos = [];
        if ($productos) {
            foreach ($productos as $producto) {
                $mapaProductos[(int)$producto['id']] = $producto;
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

                    $precio = (float)$mapaProductos[$productoId]['precio'];
                    $iva = (int)$mapaProductos[$productoId]['iva'];
                    $precioConIva = $precio * (1 + $iva / 100);

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

        $oferta = Oferta::crearOfertaConProductos(
            $nombre,
            $descripcion,
            $fechaInicio,
            $fechaFin,
            $descuentoCalculado,
            $productosOferta
        );

        if (!$oferta) {
            $this->errores[] = 'No se pudo crear la oferta.';
            return;
        }

        header('Location: ofertas.php');
        exit();
    }
}