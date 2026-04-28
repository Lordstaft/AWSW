<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioBusquedaCategoria extends Formulario
{

    public function __construct() {
        parent::__construct('formBusquedaCategoria', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/admin/busquedaCategoria.php')
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){

        $categoria = '';

        $listaCategorias = Categoria::buscaCategorias();

        foreach ($listaCategorias as $p) {
            if ($categoria === '') {
                $categoria .= "<option value='{$p->getNombre()}' selected>{$p->getNombre()}</option>";
            }

            else {
                $categoria .= "<option value='{$p->getNombre()}'>{$p->getNombre()}</option>";
            }
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Buscar categoria</legend>

                <div>
                    <label for="nombre">Buscar</label>
                    <input id="nombre" type="text" name="nombre" placeholder="Buscar por nombre"/>
                </div>

                <div>
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria">
                        $categoria
                    </select>
                </div>

                <div>
                    <button type="submit" name="buscarProducto">Buscar categoria</button>
                </div>
            </fieldset>
        EOF;
        return $html;

    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $filas = '';
        $this->errores = [];

        $nombreCategoria = filter_var($datos['nombre'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $urlModificar = $app->resuelve('/usuarios/admin/modificarCategorias.php');

        if ($nombreCategoria === '') {        
            $categoria = filter_var($datos['categoria'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $resultado = Categoria::buscaPorNombre($categoria);

            if ($resultado !== null) {
                $filas .= "<tr>
                    <td>{$resultado->getNombre()}</td>
                    <td>{$resultado->getDescripcion()}</td>
                    <td>{$resultado->getImgCategoriaProd()}</td>
                    <td>
                        <form action='{$urlModificar}' method='POST'>
                            <input type='hidden' name='id' value='{$resultado->getId()}'>
                            <button type='submit'>Modificar</button>
                        </form>
                    </td>
                </tr>";
            } 
            else {
                $this->errores[] = "No se ha podido cargar la categoría, por favor inténtelo de nuevo.";
            }
        }

        else{
            $resultado = Categoria::buscaPorNombre($nombreCategoria);
            if ($resultado !== null) {
                $filas .= "<tr>
                    <td>{$resultado->getNombre()}</td>
                    <td>{$resultado->getDescripcion()}</td>
                    <td>{$resultado->getImgCategoriaProd()}</td>
                    <td>
                        <form action='{$urlModificar}' method='POST'>
                            <input type='hidden' name='id' value='{$resultado->getId()}'>
                            <button type='submit'>Modificar</button>
                        </form>
                    </td>
                </tr>";
            } 
            else {
                $this->errores[] = "No se ha podido cargar la categoría, por favor inténtelo de nuevo.";
            }
        }

        if ($filas !== null) {
            $_SESSION['resultadosBusqueda'] = <<<EOS
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    $filas
                </tbody>
            </table>
            EOS;
        }

        else{
            $this->errores[] = 'No se han encontrado usuarios con ese nombre o rol.';
        }

    }

}
