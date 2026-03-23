<?php
namespace es\ucm\fdi\aw\usuarios;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\usuarios\Roles;

class FormularioCrearUsuario extends Formulario
{
    public function __construct() {
        if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
            parent::__construct('formEditarUsuario', ['urlRedireccion' => 'usuario.php']);
        }
        else{
            parent::__construct('formEditarUsuario', ['urlRedireccion' => RUTA_APP . '/index.php']);
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
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input id="nombreUsuario" type="text" name="nombreUsuario" value = "$nombreUsuario" />
                    {$erroresCampos['nombreUsuario']}
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" value = "$email" />
                    {$erroresCampos['email']}
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input id="password" type="password" name="password" value="$password" />
                    {$erroresCampos['password']}

                    <label for="repetirPassword">Repetir password:</label>
                    <input id="repertirPassword" type="password" name="repetirPassword"/>
                </div>
                
                $mostrarRoles

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

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || empty($password) ) {
            $this->errores['password'] = 'El password no puede estar vacío.';
        }
    
        $repetirPassword = trim($datos['repetirPassword'] ?? '');
        $repetirPassword = filter_var($repetirPassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($repetirPassword !== $password) {
            $this->errores[] = 'Las contraseñas no coinciden, vuelva a intentarlo';
        }

        if (count($this->errores) === 0) {
            $modificacion = Usuario::creaUsuario($nombreUsuario, $nombre, $password, $rol, $email, $apellidos);

            unset($_SESSION['usuarioModificado']);

            if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true){
                $usuario = Usuario::buscaUsuarioId($id);
                $_SESSION['usuarioModificado'] = $usuario->getId;
            }
        }
    }
}
