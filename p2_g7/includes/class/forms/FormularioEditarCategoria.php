<?php
namespace es\ucm\fdi\aw\formularios;

use es\ucm\fdi\aw\Categoria;

class FormularioEditarCategoria extends Formulario
{
    private $idCategoria;

    public function __construct($idCategoria)
    {
        parent::__construct('formEditarCategoria');
        $this->idCategoria = $idCategoria;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $categoria = Categoria::buscaCategoriaPorId($this->idCategoria);

        $nombre = $categoria['nombre'] ?? '';
        $descripcion = $categoria['descripcion'] ?? '';
        $imgCategoriaProd = $categoria['imgCategoriaProd'] ?? '';

        $html = <<<EOF
        <fieldset>
            <legend>Editar categoría</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="$nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required>$descripcion</textarea>

            <label for="imgCategoriaProd">Imagen:</label>
            <input type="text" name="imgCategoriaProd" value="$imgCategoriaProd">

            <button type="submit">Guardar cambios</button>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $nombre = trim($datos['nombre'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');
        $imgCategoriaProd = trim($datos['imgCategoriaProd'] ?? '');

        $errores = [];

        if ($nombre === '') {
            $errores[] = 'El nombre de la categoría no puede estar vacío';
        }

        if (count($errores) === 0) {
            $ok = Categoria::actualizaCategoria($this->idCategoria, $nombre, $descripcion, $imgCategoriaProd);

            if ($ok) {
                header('Location: index.php?pagina=listadoCategorias');
                exit();
            } else {
                $errores[] = 'No se ha podido actualizar la categoría';
            }
        }

        return $errores;
    }
}