<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Imagenes;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\pedidos\Pedido;

class FormularioPerfil extends Formulario
{

    public function __construct() {
        parent::__construct('formPerfil', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/perfil.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/perfil.php'),
            'enctype' => 'multipart/form-data'
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){

        $busqueda = $_SESSION['nombreUsuario'];
        $usuario = Usuario::buscaUsuario($busqueda);
        $pedidosPendientes = '';
        $pedidosRealizados = '';

        $filaPendientes = '';
        $filaRealizados = '';

        $rutaImagen = Aplicacion::getInstance()->resuelve("/img/" . $usuario->getAvatar());

/*         var_dump($usuario);
        exit(); */

        $pedidosPendientes = Pedido::pedidosUsuario($usuario->getId(), true);
        $pedidosRealizados = Pedido::pedidosUsuario($usuario->getId(), false);

        $tablaPendientes = '';
        $tablaRealizados = '';

        if (!empty($pedidosPendientes) && is_array($pedidosPendientes)) {
            foreach ($pedidosPendientes as $p) {
                $filaPendientes .= "<tr>
                    <td>{$p->getFechaPedido()}</td>
                    <td>{$p->getPedidoId()}</td>
                    <td>{$p->getTipo()}</td>
                    <td>{$p->getEstadoPedido()}</td>
                    <td>{$p->getTotal()}</td>
                </tr>";
            }
            $tablaPendientes = "<table border='1'>
                <thead>
                    <tr>
                        <th>Fecha del pedido</th>
                        <th>Numero de pedido</th>
                        <th>Tipo de pedido</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    $filaPendientes
                </tbody>
            </table>";
        }

        else{
            $tablaPendientes = "<p>No existen pedidos pendientes actualmente</p>";
        }

        if (!empty($pedidosRealizados) && is_array($pedidosRealizados)) {
            foreach ($pedidosRealizados as $p) {
                $filaRealizados .= "<tr>
                    <td>{$p->getFechaPedido()}</td>
                    <td>{$p->getPedidoId()}</td>
                    <td>{$p->getTipo()}</td>
                    <td>{$p->getEstadoPedido()}</td>
                    <td>{$p->getTotal()}</td>
                </tr>";
            }

            $tablaRealizados = "<table border='1'>
                <thead>
                    <tr>
                        <th>Fecha del pedido</th>
                        <th>Numero de pedido</th>
                        <th>Tipo de pedido</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    $filaRealizados
                </tbody>
            </table>";
        }
        
        else{
           $tablaRealizados = "<p>No se han realizado pedidos</p>"; 
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['email', 'imagen'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Usuario</legend>

                <div>
                    <label>Avatar</label><br>
                    <img src={$rutaImagen}>
                </div>
                
                <div>
                    <label for="nombre">Nombre:</label>
                    <input id="nombre" type="text" name="nombre" value = "{$usuario->getNombre()}" disabled/>
                </div>

                <div>
                    <label for="apellidos">Apellidos:</label>
                    <input id="apellidos" type="text" name="apellidos" value = "{$usuario->getApellidos()}" disabled/>
                </div>

                <div>
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input id="nombreUsuario" type="text" name="nombreUsuario" value = "{$usuario->getNombreUsuario()}" disabled/>
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" value = "{$usuario->getEmail()}" />
                    {$erroresCampos['email']}
                </div>

                <div>
                    <label>Eliminar imagen</label>
                        <input type="checkbox" name="eliminarImagen" value="1">
                </div>

                <div>
                    <label>Cambiar imagen:</label>
                    <input type="file" name="imagen" accept=".jpg,.jpeg,.png">
                    {$erroresCampos['imagen']}
                </div>

                <div>
                    <input type="hidden" name="id" value="{$usuario->getId()}">
                </div>

                <div>
                    <button type="submit" name="editarUsuario">Modificar</button>
                </div>
            </fieldset>

            <h2>Pedidos pendientes</h2>
            $tablaPendientes

            <h2>Pedidos realizados</h2>
            $tablaRealizados
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos){
        $app = Aplicacion::getInstance();

        if(isset($datos['editarUsuario'])){
            $this->errores = [];
            $id = $datos['id'];

            $email = trim($datos['email'] ?? '');
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errores['email'] = 'El email no es válido.';
            }

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

                $imagen = new Imagenes();
                $usuario = Usuario::buscaUsuarioId($id);
                $imagenActual = $usuario->getAvatar();

                if (!empty($datos['eliminarImagen'])) {
                    $imagen->eliminarImagen($imagenActual);
                    $nombreImagen = 'usuario_default.png';
                }
                else {
                    $nombreImagen = $imagen->reemplazarImagen($_FILES['imagen'], $imagenActual);
                }

                $modificacion = Usuario::editarUsuario($id, $usuario->getNombreUsuario(), $usuario->getNombre(), $usuario->getApellidos(), $email, $usuario->getRol(), $nombreImagen);

                if (!$modificacion) {
                    $this->errores[] = "No se ha podido modificar el usuario, por favor inténtelo de nuevo.";
                }
                else{
                    $mensajes = ['Se ha modificado el usuario correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                }
            }
        }
    }
}
