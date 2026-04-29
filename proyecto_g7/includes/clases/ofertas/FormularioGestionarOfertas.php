<?php
namespace es\ucm\fdi\aw\ofertas;
 
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\productos\Producto;
 
class FormularioGestionarOfertas extends Formulario
{
 
    public function __construct()
    {
        parent::__construct('formGestionarOfertas', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/gerente/ofertas.php'),
        ]);
    }
 
    protected function generaCamposFormulario(&$datos)
    {
        $ofertas = Oferta::listarOfertas();

        $filas = '';

        if ($ofertas) {
            foreach ($ofertas as $oferta) {
                $estado = $oferta->estaDisponible() ? 'Sí' : 'No';
                $productos = $oferta->getProductos();

                $productosTexto = '';
                if ($productos && count($productos) > 0) {
                    $partes = [];
                    foreach ($productos as $producto) {
                        $partes[] = $producto['nombreProd'] . ' x' . $producto['cantidad'];
                    }
                    $productosTexto = implode(', ', $partes);
                }
                else {
                    $productosTexto = 'Sin productos';
                }

                $filas .= "<tr>
                    <td>{$oferta->getNombre()}</td>
                    <td>{$oferta->getDescripcion()}</td>
                    <td>{$productosTexto}</td>
                    <td>{$oferta->getFechaInicio()}</td>
                    <td>{$oferta->getFechaFin()}</td>
                    <td>{$oferta->getDescuento()}%</td>
                    <td>{$estado}</td>
                    <td>" . $oferta->precioPackConIva() . " €</td>
                    <td>" . $oferta->precioFinalOferta() . " €</td>
                    <td>
                        <div>
                            <button type='submit' name='editarOferta'>Editar</button>
                            <button type='submit' name='borrarOferta' onclick=\"return confirm('¿Seguro que quieres borrar esta oferta?');\">Borrar</button>
                            <input type='hidden' name='idOferta' value='{$oferta->getIdOferta()}'>
                        </div>
                    </td>
                </tr>";
            }
        }

        $html = <<<EOS

        <div>
            <button type='submit' name='crearOferta'>Crear oferta</button>
        </div>

        <table class="tabla-ofertas">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Productos</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Descuento</th>
                    <th>Disponible</th>
                    <th>Precio pack</th>
                    <th>Precio final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                $filas
            </tbody>
        </table>
        EOS;

        return $html;
    }
 
    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        if(isset($datos['editarOferta']) || isset($datos['borrarOferta'])){
            $idOferta = trim($datos['idOferta'] ?? '');
            $idOferta = filter_var($idOferta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if(isset($datos['editarOferta'])){
                header('Location: ' . $app->resuelve("/ofertas/editarOferta.php?id=$idOferta"));
                exit();
            }
            
            elseif(isset($datos['borrarOferta'])){
                Oferta::eliminarOferta($idOferta);
                header('Location: ' . $app->resuelve('/usuarios/gerente/ofertas.php'));
                exit();
            }
        }

        elseif(isset($datos['crearOferta'])){
            header('Location: ' . $app->resuelve('/ofertas/crearOferta.php'));
            exit();
        }

    }
}