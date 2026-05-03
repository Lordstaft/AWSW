<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Imagenes;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioEditarCategoria extends Formulario
{
    private $idCategoria;
    private $categoria;

    public function __construct($idCategoria)
    {
        parent::__construct('formEditarCategoria');
            parent::__construct('formEditarCategoria', [
                'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/admin/busquedaCategoria.php'),
                'enctype' => 'multipart/form-data'
         ]);
         $this->idCategoria = (int)$idCategoria;
         $this->categoria = Categoria::buscaPorId($this->idCategoria);
    }

    protected function generaCamposFormulario(&$datos)
    {

        $rutaImagen = Aplicacion::getInstance()->resuelve("/img/" . $this->categoria->getImgCategoriaProd());

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'descripcion', 'imagen'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Editar categoría</legend>

            <div>
                <label>Imagen actual:</label><br>
                <img src={$rutaImagen} alt="Imagen de la categoría">
            </div>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="validar-categoria-editar" name="nombre" value="{$this->categoria->getNombre()}">
            {$erroresCampos['nombre']}
            <span id="categoriaEditarCorrecta"></span>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required>{$this->categoria->getDescripcion()}</textarea>
            {$erroresCampos['descripcion']}

            <div>
                <label>Cambiar imagen:</label>
                <input type="file" name="imagen" accept=".jpg,.jpeg,.png">
            </div>
            {$erroresCampos['imagen']}

            <input type="hidden" id="idCategoria" name="id" value="{$this->categoria->getId()}">

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

        $id = trim($datos['id']);
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


        if (count($this->errores) === 0) {

            if(isset($datos['editarCategoria'])){
                $imagen = new Imagenes();
                $nombreImagen = $imagen->reemplazarImagen($_FILES['imagen'], $this->categoria->getImgCategoriaProd());

                $ok = Categoria::actualiza($id, $nombre, $descripcion, $nombreImagen);

                if ($ok) {
                    $mensajes = ['Se ha actualizado la categoría correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                } else {
                    $this->errores[] = 'No se ha podido actualizar la categoría';
                }
            }
            elseif(isset($datos['eliminarCategoria'])){
                $imagen = new Imagenes();
                $this->categoria = Categoria::buscaPorId($id);
                $imagen->eliminarImagen($this->categoria->getImgCategoriaProd());
                
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
