<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Imagenes;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioCrearUsuario extends Formulario
{
    public function __construct() {
        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
            parent::__construct('formEditarUsuario', [
                'action' => Aplicacion::getInstance()->resuelve('/registro.php'),
                'urlRedireccion' => Aplicacion::getInstance()->resuelve('/usuarios/admin.php'),
                'enctype' => 'multipart/form-data'
            ]);
        }
        else{
            parent::__construct('formEditarUsuario', [
                'action' => Aplicacion::getInstance()->resuelve('/registro.php'),
                'urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php'),
                'enctype' => 'multipart/form-data'
            ]);
        }
    }
    
    protected function generaCamposFormulario(&$datos){
        $roles = '';
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';
        $password = $datos['password'] ?? '';
        $email = $datos['email'] ?? '';
        $apellidos = $datos['nombreUsuario'] ?? '';
        $mostrarRoles = '';

        foreach (Roles::cases() as $rol) {
            if ($rol->value !== Roles::ADMIN->value) {
                if($rol->value === Roles::CLIENTE->value){
                    $roles .= "<option value='{$rol->value}' selected>{$rol->value}</option>";
                }
                else{
                    $roles .= "<option value='{$rol->value}'>{$rol->value}</option>";
                }
            }
        }

        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
            $mostrarRoles = "<div>
                <label for='rol'>Rol:</label>
                    <select id='rol' name='rol'>
                    $roles
                </select>
            </div>";
        }

        else{
            $roles = Roles::CLIENTE->value;
            $mostrarRoles = "<div>
                <input type='hidden' name='rol' value='{$roles}'>
            </div>";
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'apellidos', 'email', 'password'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Usuario</legend>
                
                <div>
                    <label for="nombre">Nombre:</label>
                    <input id="nombre" type="text" name="nombre" value = "$nombre" />
                    {$erroresCampos['nombre']}
                </div>

                <div>
                    <label for="apellidos">Apellidos:</label>
                    <input id="apellidos" type="text" name="apellidos" value = "$apellidos" />
                    {$erroresCampos['apellidos']}
                </div>

                <div>
                    <label for="nombreUsuario" class="validar-usuario">Nombre de usuario:</label>
                    <input id="nombreUsuario" class="validar-usuario" type="text" name="nombreUsuario" value = "$nombreUsuario" />
                    {$erroresCampos['nombreUsuario']}
                    <span id="usuarioCorrecto"></span>
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" value = "$email" />
                    {$erroresCampos['email']}
                    <span id="formatoEmail"></span>
                    <span id="emailCorrecto"></span>
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input id="password" type="password" name="password" value="$password" />
                    {$erroresCampos['password']}

                    <label for="repetirPassword">Repetir password:</label>
                    <input id="repetirPassword" type="password" name="repetirPassword"/>
                </div>
                
                $mostrarRoles

                <div>
                    <label>Subir avatar:</label>
                    <input type="file" name="imagen" accept=".jpg,.jpeg,.png">
                </div>

                <div>
                    <button type="submit" name="crearUsuario">Crear</button>
                </div>
            </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos){

        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || empty($nombreUsuario) ) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }

        $resultado = Usuario::buscaUsuario($nombreUsuario);
        if ($resultado) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario ya existe';
        }
        
        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombre || empty($nombre) ) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío.';
        }

        $apellidos = trim($datos['apellidos'] ?? '');
        $apellidos = filter_var($apellidos, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$apellidos || empty($apellidos) ) {
            $this->errores['apellidos'] = 'El apellido no puede estar vacío.';
        }


        $rol = trim($datos['rol'] ?? '');
        $rol = filter_var($rol, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errores['email'] = 'El email no es válido.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$password || empty($password) ) {
            $this->errores['password'] = 'El password no puede estar vacío.';
        }
    
        $repetirPassword = trim($datos['repetirPassword'] ?? '');
        $repetirPassword = filter_var($repetirPassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($repetirPassword !== $password) {
            $this->errores[] = 'Las contraseñas no coinciden, vuelva a intentarlo';
        }

        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();

            $imagenNombre = 'usuario_default.jpg';
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
            $resul = Usuario::creaUsuario($nombreUsuario, $nombre, $password, $rol, $email, $apellidos, $imagenNombre);

            if(!$resul){
                $this->errores[] = 'Error al intentar crear el usuario';
                if ($imagenNombre !== 'usuario_default.jpg') {
                    $imgHandler->eliminarImagen($imagenNombre);
                }
            }

            else{
                $mensajes = ['Se ha creado el usuario correctamente.'];
                $app->putAtributoPeticion('mensajes', $mensajes);
            }
        }
    }
}
