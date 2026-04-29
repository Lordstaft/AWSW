<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Categoria;
use es\ucm\fdi\aw\Imagenes;

class FormularioEditarProducto extends Formulario
{

    public function __construct() {
        parent::__construct('formEditarProducto', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/modificarProductos.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/productos.php'),
            'enctype' => 'multipart/form-data'
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $busqueda = $datos['id'] ?? $_POST['id'] ?? '';
        $producto = Producto::buscaPorId($busqueda);
        $categorias = Categoria::listar();
        $buttonEliminar = '';

        $opcionesCategorias = '';
        foreach ($categorias as $categoria) {
            if($categoria->getId() === $producto->getCategoriaId()) {
                $opcionesCategorias .= "<option value='{$categoria->getId()}' selected>{$categoria->getNombre()}</option>";
            }

            else {
                $opcionesCategorias .= "<option value='{$categoria->getId()}'>{$categoria->getNombre()}</option>";
            }
        }

        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] == true){
            $buttonEliminar = '<button type="submit" name="eliminarProducto">Eliminar producto</button>';
        }

        $rutaImagen = Aplicacion::getInstance()->resuelve("/img/" . $producto->getRutaImagen());


        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreProd', 'descripcion', 'precio', 'iva', 'disponible', 'ofertado', 'imagen'], $this->errores, 'span', array('class' => 'error'));


        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Editar producto</legend>

                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombreProd" value="{$producto->getNombreProd()}" required>
                </div>

                <div>
                    <label>Descripción:</label>
                    <textarea name="descripcion" required>{$producto->getDescripcion()}</textarea>
                    {$erroresCampos['descripcion']}
                </div>

                <div>
                    <label>Categoría:</label>
                    <select name="categoria_id" required>
                        $opcionesCategorias
                    </select>
                </div>

                <div>
                    <label>Precio:</label>
                    <input type="number" step="0.01" name="precio" value="{$producto->getPrecio()}" required>
                    {$erroresCampos['precio']}
                </div>

                <div>
                    <label>IVA:</label>
                    <select name="iva" required>
                        <option value="4" {$this->selected($producto->getIva(), 4)}>4</option>
                        <option value="10" {$this->selected($producto->getIva(), 10)}>10</option>
                        <option value="21" {$this->selected($producto->getIva(), 21)}>21</option>
                    </select>
                    {$erroresCampos['iva']}
                </div>

                <div>
                    <label>Stock:</label>
                    <input type="number" name="stock" value="{$producto->getStock()}" min="0" required>
                </div>

                <div>
                    <label>Disponible</label>
                        <input type="checkbox" name="disponible" value="1" {$this->checked($producto->getDisponible())}>
                        {$erroresCampos['disponible']}
                </div>

                <div>
                    <label>Ofertado</label>
                        <input type="checkbox" name="ofertado" value="1" {$this->checked($producto->getOfertado())}>
                        {$erroresCampos['ofertado']}
                </div>

                <input type="hidden" name="id" value="{$producto->getId()}">

                <div>
                    <label>Imagen actual:</label><br>
                    <img src={$rutaImagen} alt="Imagen del producto">
                </div>
                {$erroresCampos['imagen']}

                <div>
                    <label>Eliminar imagen</label>
                        <input type="checkbox" name="eliminarImagen" value="1">
                </div>

                <div>
                    <label>Cambiar imagen:</label>
                    <input type="file" name="imagen" accept=".jpg,.jpeg,.png">
                </div>

                <div>
                    <button type="submit" name="editarProducto">Guardar cambios</button>
                    $buttonEliminar
                    <button type="submit" name="retirarProducto">Retirar</button>
                </div>

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

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        $this->errores = [];

        if(isset($datos['editarProducto'])){

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

            $categoria_id = filter_var($datos['categoria_id'] ?? '');
            $categoria_id = filter_var($categoria_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($categoria_id <= 0) {
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
            
            $id = filter_var($datos['id'] ?? '');
            $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {

                if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                    $this->errores['imagen'] = 'Error al subir la imagen';
                } 
                else {
                    $mime = mime_content_type($_FILES['imagen']['tmp_name']);

                    $mimesPermitidos = ['image/jpeg', 'image/png'];

                    if (!in_array($mime, $mimesPermitidos)) {
                        $this->errores['imagen'] = 'Solo se permiten imágenes JPG o PNG';
                    }

                    if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
                        $this->errores['imagen'] = 'La imagen es demasiado grande';
                    }
                }
            }

            $imagen = new Imagenes();

            $disponible = isset($datos['disponible']) ? 1 : 0;
            $ofertado = isset($datos['ofertado']) ? 1 : 0;

            $producto = Producto::buscaPorId($id);
            $imagenActual = $producto->getRutaImagen() ?? '';

            if (count($this->errores) === 0) {
                if (!empty($datos['eliminarImagen'])) {
                    $imagen->eliminarImagen($imagenActual);
                    $nombreImagen = 'producto_default.jpg';
                    }
                else {
                    $nombreImagen = $imagen->reemplazarImagen($_FILES['imagen'], $imagenActual);
                }

                $ok = Producto::actualiza(
                    $id,
                    $nombreProd,
                    $descripcion,
                    $categoria_id,
                    $precio,
                    $iva,
                    $stock,
                    $disponible,
                    $ofertado,
                    $nombreImagen
                );

                if ($ok) {
                    $mensajes = ['Se ha modificado el producto correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                    unset($_SESSION['resultadosBusqueda']);
                } 
                else {
                    $this->errores[] = "No se ha podido modificar el producto, por favor inténtelo de nuevo.";
                }
            }
        }

        if(isset($datos['retirarProducto'])){
            $id = filter_var($datos['id'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $resul = Producto::retirar($id);

            if(!$resul){
                $this->errores[] = "No se ha podido retirar el producto, por favor inténtelo de nuevo.";
            }
            else{
                $mensajes = ['Se ha retirado el producto correctamente.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                unset($_SESSION['resultadosBusqueda']);
            }
        }

        if(isset($datos['eliminarProducto'])){
            $id = filter_var($datos['id'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $resul = Producto::borra($id);

            if(!$resul){
                $this->errores[] = "No se ha podido eliminar el producto, por favor inténtelo de nuevo.";
            }
            else{
                $mensajes = ['Se ha eliminado el producto correctamente.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                unset($_SESSION['resultadosBusqueda']);
            }
        }
    }

}
