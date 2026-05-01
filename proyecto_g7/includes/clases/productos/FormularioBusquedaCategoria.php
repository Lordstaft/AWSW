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

        $listaCategorias = Categoria::buscaCategorias();
        $filas = '';

        if (!empty($listaCategorias) && is_array($listaCategorias)) {
            foreach ($listaCategorias as $c) {
                $filas .= "<tr>
                    <td>{$c->getNombre()}</td>
                    <td>{$c->getDescripcion()}</td>
                    <td>{$c->getImgCategoriaProd()}</td>
                    <td>
                        <div>
                            <button type='submit' name='id' value='{$c->getId()}'>Modificar</button>
                        </div>
                    </td>
                </tr>";
            }
        }
        
        if($filas === ''){
            $html = <<<EOF
                <p>No hay categorías.</p>
            EOF;
        }
        else {
            $html = <<<EOF
            <table class="tabla-general">
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
            EOF;
        }

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idCategoria = trim($datos['id'] ?? '');
        $idCategoria = filter_var($idCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(!$idCategoria || empty($idCategoria)){
            $mensajes = ['Error al localizar la categoría.'];
            $app->putAtributoPeticion('mensajes', $mensajes);
            $app->redirige($app->resuelve('/usuarios/admin/busquedaCategoria.php'));
        }
        else {
            header('Location: ' . $app->resuelve("/usuarios/admin/modificarCategorias.php?id=$idCategoria"));
            exit();
        }
    }
}