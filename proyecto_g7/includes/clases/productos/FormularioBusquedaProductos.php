<?php
namespace es\ucm\fdi\aw\productos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\productos\Producto;
use es\ucm\fdi\aw\productos\Categoria;

class FormularioBusquedaProductos extends Formulario
{

    public function __construct() {
        parent::__construct('formBusquedaProductos', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/busquedaProductos.php'),
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){

        $categoria = '';
        $categoria .= "<option value='' selected>Todos</option>";

        $listaCategorias = Categoria::buscaCategorias();

        foreach ($listaCategorias as $p) {
            $categoria .= "<option value='{$p->getNombre()}'>{$p->getNombre()}</option>";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Buscar producto</legend>

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
                    <button type="submit" name="buscarProducto">Buscar producto</button>
                </div>
            </fieldset>
        EOF;
        return $html;

    }

    protected function procesaFormulario(&$datos)
    {
        $filas = '';
        $this->errores = [];

        $app = Aplicacion::getInstance();

        $nombreProducto = filter_var($datos['nombre'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $urlModificar = Aplicacion::getInstance()->resuelve($app->resuelve('/usuarios/gerente/modificarProductos.php'));

        if ($nombreProducto === '') {        
            $categoria = filter_var($datos['categoria'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($categoria !== 'Todos' && $categoria !== ''){
                $resultado = Categoria::buscaPorNombre($categoria);
                $productos = Producto::listar($resultado->getId());
            }

            else{
                $productos = Producto::listar($categoria);
            }

            if (!empty($productos) && is_array($productos)) {
                foreach ($productos as $p) {
                    $filas .= "<tr>
                        <td>{$p->getNombreProd()}</td>
                        <td>{$p->getDescripcion()}</td>
                        <td>{$p->getPrecio()}</td>
                        <td>{$p->getStock()}</td>
                        <td>
                            <form action='{$urlModificar}' method='POST'>
                                <input type='hidden' name='id' value='{$p->getId()}'>
                                <button type='submit'>Modificar</button>
                            </form>
                        </td>
                    </tr>";
                }
            } 
            else {
                $filas = null;
            }
        }

        else{
            $nombreProducto = Producto::buscaPorNombre($nombreProducto);
            if ($nombreProducto !== null) {
                $filas .= "<tr>
                    <td>{$nombreProducto->getNombreProd()}</td>
                    <td>{$nombreProducto->getDescripcion()}</td>
                    <td>{$nombreProducto->getPrecio()}</td>
                    <td>{$nombreProducto->getStock()}</td>
                    <td>
                        <form action='{$urlModificar}' method='POST'>
                            <input type='hidden' name='id' value='{$nombreProducto->getId()}'>
                            <button type='submit'>Modificar</button>
                        </form>
                    </td>
                </tr>";
            } 
            else {
                $filas = null;
            }
        }

        if ($filas !== null) {
            $_SESSION['resultadosBusqueda'] = <<<EOS
            <div class="tabla-wrapper">
                <table class="tabla-general">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        $filas
                    </tbody>
                </table>
            </div>
            EOS;
        }

        else{
            $this->errores[] = 'No se han encontrado usuarios con ese nombre o rol.';
        }

    }

}
