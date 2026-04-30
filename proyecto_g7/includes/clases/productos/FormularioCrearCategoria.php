<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Imagenes;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioCrearCategoria extends Formulario
{
    public function __construct() {
        parent::__construct('formCrearCategoria', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/admin/registroCategoria.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('usuarios/admin/categorias.php'),
            'enctype' => 'multipart/form-data'
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){
        $nombreCategoria = $datos['nombreCategoria'] ?? '';
        $descripcionCategoria = $datos['descripcionCategoria'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreCategoria', 'descripcionCategoria'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Crear categoría</legend>
                
                <div>
                    <label for="nombreCategoria">Nombre:</label>
                    <input id="nombreCategoria" type="text" name="nombreCategoria" value = "$nombreCategoria" />
                    {$erroresCampos['nombreCategoria']}
                </div>

                <div>
                    <label for="descripcionCategoria">Descripción:</label>
                    <input id="descripcionCategoria" type="text" name="descripcionCategoria" value = "$descripcionCategoria" />
                    {$erroresCampos['descripcionCategoria']}
                </div>

                <div>
                    <label>Subir imagen:</label>
                    <input type="file" name="imagen" accept=".jpg,.jpeg,.png" required>
                </div>

                <div>
                    <button type="submit" name="crearCategoria">Crear</button>
                </div>
            </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos){

        $this->errores = [];
        $app = Aplicacion::getInstance();

        $nombreCategoria = trim($datos['nombreCategoria'] ?? '');
        $nombreCategoria = filter_var($nombreCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombreCategoria || empty($nombreCategoria) ) {
            $this->errores['nombreCategoria'] = 'El nombre de la categoría no puede estar vacío';
        }

        $resultado = Categoria::buscaPorNombre($nombreCategoria);
        if ($resultado) {
            $this->errores['nombreCategoria'] = 'Ya existe una categoría con ese nombre';
        }

        $descripcionCategoria = trim($datos['descripcionCategoria'] ?? '');
        $descripcionCategoria = filter_var($descripcionCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$descripcionCategoria || empty($descripcionCategoria) ) {
            $this->errores['descripcionCategoria'] = 'La descripción de la categoría no puede estar vacía';
        }

        if (count($this->errores) === 0) {
            $imagenNombre = 'categoria_default.jpg';
            if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $mimesPermitidos = ['image/jpeg', 'image/png'];
                $mime = mime_content_type($_FILES['imagen']['tmp_name']);
                if (in_array($mime, $mimesPermitidos) && $_FILES['imagen']['size'] <= 5 * 1024 * 1024) {
                    $imgHandler = new Imagenes();
                    $subido = $imgHandler->subirImagen($_FILES['imagen']);
                    if ($subido) {
                        $imagenNombre = $subido;
                    }
                }
            }

            $resul = Categoria::crea($nombreCategoria, $descripcionCategoria, $imagenNombre);

            if(!$resul){
                $this->errores[] = "No se ha podido crear la categoría. Inténtalo de nuevo.";
                if($imagenNombre !== 'categoria_default.jpg'){
                    $imgHandler->eliminarImagen($imagenNombre);
                }
            }

            else{
                $mensajes = ['Se ha creado la categoría correctamente.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
            }
        }
    }
}
