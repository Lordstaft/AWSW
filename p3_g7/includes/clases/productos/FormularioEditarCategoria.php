<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioEditarCategoria extends Formulario
{
    public function __construct()
    {
        parent::__construct('formEditarCategoria');
            parent::__construct('formEditarCategoria', [
                'action' => Aplicacion::getInstance()->resuelve('/usuarios/admin/modificarCategorias.php'),
                'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/admin.php')
         ]);
        }

    protected function generaCamposFormulario(&$datos)
    {
        $busqueda = $datos['id'] ?? $_POST['id'] ?? '';

        $categoria = Categoria::buscaPorId($busqueda);

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Editar categoría</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="{$categoria->getNombre()}" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required>{$categoria->getDescripcion()}</textarea>

            <label for="imgCategoriaProd">Imagen:</label>
            <input type="text" name="imgCategoriaProd" value="{$categoria->getImgCategoriaProd()}">

            <input type="hidden" name="id" value="{$categoria->getId()}">

            <button type="submit" name="editarCategoria">Guardar cambios</button>
            <button type="submit" name="eliminarCategoria">Eliminar categoría</button>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombre || empty($nombre) ) {
            $this->errores['nombre'] = 'El nombre de la categoría no puede estar vacío';
        }

        $descripcion = trim($datos['descripcion'] ?? '');
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$descripcion || empty($descripcion) ) {
            $this->errores['descripcion'] = 'La descripción de la categoría no puede estar vacía';
        }

        $imgCategoriaProd = trim($datos['imgCategoriaProd'] ?? '');
        $imgCategoriaProd = filter_var($imgCategoriaProd, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$imgCategoriaProd || empty($imgCategoriaProd) ) {
            $this->errores['imgCategoriaProd'] = 'La imagen de la categoría no puede estar vacía';
        }

        $id = trim($datos['id']);
        $id = filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        if (count($this->errores) === 0) {
            if(isset($datos['editarCategoria'])){
                $ok = Categoria::actualiza($id, $nombre, $descripcion, $imgCategoriaProd);

                if ($ok) {
                    $mensajes = ['Se ha actualizado la categoría correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                } else {
                    $this->errores[] = 'No se ha podido actualizar la categoría';
                }
            }
            elseif(isset($datos['eliminarCategoria'])){
                $ok = Categoria::borra($id);

                if ($ok) {
                    $mensajes = ['Se ha eliminado la categoría correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                } else {
                    $this->errores[] = 'No se ha podido eliminar la categoría';
                }
            }
        }
    }
}
