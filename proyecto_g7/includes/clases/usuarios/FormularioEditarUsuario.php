<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Imagenes;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\usuarios\Roles;

class FormularioEditarUsuario extends Formulario
{

    public function __construct() {
        parent::__construct('formEditarUsuario', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/admin/modificarUsuario.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/admin.php'),
            'enctype' => 'multipart/form-data'
        ]);
    }
    
    protected function generaCamposFormulario(&$datos){
        $busqueda = $datos['id'] ?? $_POST['id'] ?? '';
        $usuario = Usuario::buscaUsuarioId($busqueda);
        $roles = '';
        foreach (Roles::cases() as $rol) {
            if ($usuario->getRol() === $rol->value) {
                $roles .= "<option value='{$rol->value}' selected>{$rol->value}</option>";
            } else {
                $roles .= "<option value='{$rol->value}'>{$rol->value}</option>";
            }
        }

        $rutaImagen = Aplicacion::getInstance()->resuelve("/img/" . $usuario->getAvatar());

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'apellidos', 'email', 'imagen'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Usuario</legend>

                <div>
                    <label>Avatar</label><br>
                    <img src={$rutaImagen} class="avatar-usuario" alt="Imagen de perfil">
                </div>
                
                <div>
                    <label for="nombre">Nombre:</label>
                    <input id="nombre" type="text" name="nombre" value = "{$usuario->getNombre()}" />
                    {$erroresCampos['nombre']}
                </div>

                <div>
                    <label for="apellidos">Apellidos:</label>
                    <input id="apellidos" type="text" name="apellidos" value = "{$usuario->getApellidos()}" />
                    {$erroresCampos['apellidos']}
                </div>

                <div>
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input id="nombreUsuario" type="text" name="nombreUsuario" value = "{$usuario->getNombreUsuario()}" />
                    {$erroresCampos['nombreUsuario']}
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" value = "{$usuario->getEmail()}" />
                    {$erroresCampos['email']}
                </div>

                <div>
                    <label for="rol">Rol:</label>
                        <select id="rol" name="rol">
                        $roles
                    </select>
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
                    <button type="submit" name="eliminarUsuario">Eliminar</button>
                </div>
            </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos){
        $app = Aplicacion::getInstance();

        if(isset($datos['editarUsuario'])){
            $this->errores = [];
            $id = $datos['id'];

            $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
            $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $nombreUsuario || empty($nombreUsuario) ) {
                $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
            }
            
            $nombre = trim($datos['nombre'] ?? '');
            $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $nombre || empty($nombre) ) {
                $this->errores['nombre'] = 'El nombre no puede estar vacío.';
            }

            $apellidos = trim($datos['apellidos'] ?? '');
            $apellidos = filter_var($apellidos, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $apellidos || empty($apellidos) ) {
                $this->errores['apellidos'] = 'El apellido no puede estar vacío.';
            }

            $rol = trim($datos['rol'] ?? '');
            $rol = filter_var($rol, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
                    $nombreImagen = null;
                    }
                else {
                    $nombreImagen = $imagen->reemplazarImagen($_FILES['imagen'], $imagenActual);
                }

                $modificacion = Usuario::editarUsuario($id, $nombreUsuario, $nombre, $apellidos, $email, $rol, $nombreImagen);

                if (!$modificacion) {
                    $this->errores[] = "No se ha podido modificar el usuario, por favor inténtelo de nuevo.";
                }
                else{
                    $mensajes = ['Se ha modificado el usuario correctamente.'];
                    $app->putAtributoPeticion('mensajes', $mensajes);
                }
            }
        }

        elseif(isset($datos['eliminarUsuario'])){
            $id = $datos['id'];
            $resul = Usuario::eliminarUsuario($id);
            if(!$resul){
                $this->errores[] = "No se ha podido eliminar el usuario, por favor inténtelo de nuevo.";
            }
            else{
                $mensajes = ['Se ha eliminado el usuario correctamente.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
                unset($_SESSION['resultadosBusqueda']);
            }
        }
    }
}
