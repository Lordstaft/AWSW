<?php
namespace es\ucm\fdi\aw\forms;
use es\ucm\fdi\aw\Producto;
use es\ucm\fdi\aw\Categoria;

class FormularioEditarProducto extends Formulario
{
    private $idProducto;

    public function __construct($idProducto)
    {
        parent::__construct('formEditarProducto');
        $this->idProducto = $idProducto;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $producto = Producto::buscaPorId($this->idProducto);
        $categorias = Categoria::listar(); 

        $nombreProd = $producto['nombreProd'] ?? '';
        $descripcion = $producto['descripcion'] ?? '';
        $categoriaSeleccionada = $producto['categoria_id'] ?? '';
        $precio = $producto['precio'] ?? '';
        $iva = $producto['iva'] ?? '';
        $stock = $producto['stock'] ?? '';
        $disponible = $producto['disponible'] ?? 1;
        $ofertado = $producto['ofertado'] ?? 0;

        $opcionesCategorias = '';
        foreach ($categorias as $categoria) {
            $selected = ($categoria['id'] == $categoriaSeleccionada) ? 'selected' : '';
            $opcionesCategorias .= "<option value='{$categoria['id']}' $selected>{$categoria['nombre']}</option>";
        }

        $checkedDisponible = $disponible ? 'checked' : '';
        $checkedOfertado = $ofertado ? 'checked' : '';

        $html = <<<EOF
        <fieldset>
            <legend>Editar producto</legend>

            <label>Nombre:</label>
            <input type="text" name="nombreProd" value="$nombreProd" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required>$descripcion</textarea>

            <label>Categoría:</label>
            <select name="categoria_id" required>
                $opcionesCategorias
            </select>

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="$precio" required>

            <label>IVA:</label>
            <select name="iva" required>
                <option value="4" {$this->selected($iva, 4)}>4</option>
                <option value="10" {$this->selected($iva, 10)}>10</option>
                <option value="21" {$this->selected($iva, 21)}>21</option>
            </select>

            <label>Stock:</label>
            <input type="number" name="stock" value="$stock" min="0" required>

            <label>
                <input type="checkbox" name="disponible" value="1" $checkedDisponible>
                Disponible
            </label>

            <label>
                <input type="checkbox" name="ofertado" value="1" $checkedOfertado>
                Ofertado
            </label>

            <button type="submit">Guardar cambios</button>
        </fieldset>
        EOF;

        return $html;
    }

    private function selected($valorActual, $valorOpcion)
    {
        return ((int)$valorActual === (int)$valorOpcion) ? 'selected' : '';
    }

    protected function procesaFormulario(&$datos)
    {
        $nombreProd = trim($datos['nombreProd'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $categoria_id = (int)($datos['categoria_id'] ?? 0);
        $precio = (float)($datos['precio'] ?? 0);
        $iva = (int)($datos['iva'] ?? 0);
        $stock = (int)($datos['stock'] ?? 0);
        $disponible = isset($datos['disponible']) ? 1 : 0;
        $ofertado = isset($datos['ofertado']) ? 1 : 0;

        $errores = [];

        if ($nombreProd === '') {
            $errores[] = 'El nombre del producto no puede estar vacío';
        }
        if ($categoria_id <= 0) {
            $errores[] = 'Debe seleccionarse una categoría válida';
        }
        if ($precio < 0) {
            $errores[] = 'El precio no puede ser negativo';
        }
        if ($stock < 0) {
            $errores[] = 'El stock no puede ser negativo';
        }

        if (count($errores) === 0) {
            $ok = Producto::actualiza(
                $this->idProducto,
                $nombreProd,
                $descripcion,
                $categoria_id,
                $precio,
                $iva,
                $stock,
                $disponible,
                $ofertado
            );

            if ($ok) {
                header('Location: index.php?pagina=listadoProductos');
                exit();
            } else {
                $errores[] = 'No se ha podido actualizar el producto';
            }
        }

        return $errores;
    }

}
