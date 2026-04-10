<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioCrearProducto extends Formulario
{
    public function __construct() {
        parent::__construct('formCrearProducto', [
        'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/registroProducto.php'),
        'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/productos.php'),
        'enctype' => 'multipart/form-data'
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){
        $categoria = '';

        $listaCategorias = Categoria::buscaCategorias();

        $nombreProducto = $datos['nombreProducto'] ?? '';
        $descripcionProducto = $datos['descripcionProducto'] ?? '';
        $precioProducto = $datos['precioProducto'] ?? '';
        $imgProducto = $datos['imgProducto'] ?? '';
        $iva = $datos['iva'] ?? '';
        $stock = $datos['stock'] ?? '';
        $disponible = $datos['disponible'] ?? '';
        $ofertado = $datos['ofertado'] ?? '';

        foreach ($listaCategorias as $p) {
            if ($categoria === '') {
                $categoria .= "<option value='{$p->getNombre()}' selected>{$p->getNombre()}</option>";
            }

            else {
                $categoria .= "<option value='{$p->getNombre()}'>{$p->getNombre()}</option>";
            }
        }


        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreProd', 'categoria', 'descripcion', 'precio', 'iva', 'disponible', 'ofertado', 'stock', 'imgProducto'], $this->errores, 'span', array('class' => 'error'));


        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Crear producto</legend>

            <label>Nombre:</label>
            <input type="text" name="nombreProd" value="{$nombreProducto}" required>
            {$erroresCampos['nombreProd']}

            <label>Descripción:</label>
            <textarea name="descripcion" required>{$descripcionProducto}</textarea>
            {$erroresCampos['descripcion']}

            <label>Categoría:</label>
            <select name="categoria_id" required>
                $categoria
            </select>
            {$erroresCampos['categoria']}

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="{$precioProducto}" required>
            {$erroresCampos['precio']}

            <label>IVA:</label>
            <select name="iva" required>
                <option value="4" {$this->selected($iva, 4)}>4</option>
                <option value="10" {$this->selected($iva, 10)}>10</option>
                <option value="21" {$this->selected($iva, 21)}>21</option>
                {$erroresCampos['iva']}
            </select>

            <label>Stock:</label>
            <input type="number" name="stock" value="{$stock}" min="0" required>
            {$erroresCampos['stock']}

            <label>
                <input type="checkbox" name="disponible" value="1" {$this->checked($disponible)}>
                Disponible
                {$erroresCampos['disponible']}
            </label>

            <label>
                <input type="checkbox" name="ofertado" value="1" {$this->checked($ofertado)}>
                Ofertado
                {$erroresCampos['ofertado']}
            </label>

            <label>Subir imagen:</label>
            <input type="file" name="imagen" accept=".jpg,.jpeg,.png">
            {$erroresCampos['imgProducto']}

            <button type="submit" name="crearProducto">Crear producto</button>
        </fieldset>
        EOF;

        return $html;
    }

    private function selected($valorActual, $valorOpcion)
    {
        return ((int)$valorActual === (int)$valorOpcion) ? 'selected' : '';
    }

    private function checked($valorActual)
    {
        return ((int)$valorActual === 1) ? 'checked' : '';
    }

    protected function procesaFormulario(&$datos){
        $app = Aplicacion::getInstance();
        $this->errores = [];

        $nombreProd = trim($datos['nombreProd'] ?? '');
        $nombreProd = filter_var($nombreProd, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreProd || empty($nombreProd) ) {
            $this->errores['nombreProd'] = 'El nombre del producto no puede estar vacío';
        }

        $descripcion = trim($datos['descripcion'] ?? '');
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $descripcion || empty($descripcion) ) {
            $this->errores['descripcion'] = 'La descripción del producto no puede estar vacía';
        }

        $nombreCategoria = filter_var($datos['categoria_id'] ?? '');
        $nombreCategoria = filter_var($nombreCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($nombreCategoria <= 0) {
            $this->errores['categoria_id'] = 'Debe seleccionarse una categoría válida';
        }

        $precio = filter_var($datos['precio'] ?? '');
        $precio = filter_var($precio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($precio < 0) {
            $this->errores['precio'] = 'El precio no puede ser negativo';
        }

        $iva = filter_var($datos['iva'] ?? '');
        $iva = filter_var($iva, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!in_array($iva, [4, 10, 21])) { 
            $this->errores['iva'] = 'El IVA debe ser 4, 10 o 21';
        }

        $stock = filter_var($datos['stock'] ?? '');
        $stock = filter_var($stock, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($stock < 0) {
            $this->errores['stock'] = 'El stock no puede ser negativo';
        }
        
        $id = filter_var($datos['id'] ?? '');
        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $disponible = isset($datos['disponible']) ? 1 : 0;
        $ofertado = isset($datos['ofertado']) ? 1 : 0;

        $categoria = Categoria::buscaPorNombre($nombreCategoria);

        if (count($this->errores) === 0) {

            if(isset($datos['crearProducto'])){
                $app = Aplicacion::getInstance();
                $resul = Producto::creaProducto($nombreProd, $descripcion, $categoria->getId(), $precio, $iva, $stock, $disponible, $ofertado);

                if(!$resul){
                    $this->errores[] = "No se ha podido crear el producto, por favor inténtelo de nuevo.";
                }

                else{
                    $mensajes = ['Se ha creado el producto correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                }
            }
        }
    }
}
