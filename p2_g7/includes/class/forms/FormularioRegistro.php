<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__ . '/../Usuario.php';
require_once __DIR__ . '/../Roles.php';

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }
    protected function generaCamposFormulario(&$datos) {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';
        // Terminar variables
      $apellidos = 

        $erroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'apellidos', 'email', 'password', 'password2'], $this->errores, 'span', array('class' => 'error'));
    
        $contenidoPrincipal = <<<EOF
        $erroresGlobales
        <fieldset>
            <legend>Datos para el registro</legend>
                    
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario"/>
                {$erroresCampos['nombreUsuario']}
            </div>
                    
            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre"/>
                {$erroresCampos['nombre']}
            </div>

            <div>
                <label for="apellidos">Apellidos:</label>
                <input id="apellidos" type="text" name="apellidos" value="$apellidos"/>
                {$erroresCampos['apellidos']}
            </div>          

            <div>
                <label for="email">Email:</label>
                <input id="email" type="email" name="email" value="$email"/>
                {$erroresCampos['email']}
            </div>
          
            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            
            <div>
                <label for="password2">Reintroduce el password:</label>
                <input id="password2" type="password" name="password2" />
                {$erroresCampos['password2']}
            </div>
            
            <div>
                <button type="submit" name="registro">Registrar</button>
            </div>
        </fieldset>
        EOF;
        return $contenidoPrincipal;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombreUsuario = filter_input(INPUT_POST, 'nombreUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || empty($nombreUsuario=trim($nombreUsuario)) || mb_strlen($nombreUsuario) < 5) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.';
        }

        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || empty($nombre=trim($nombre)) || mb_strlen($nombre) < 5) {
            $this->errores['nombre'] = 'El nombre tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || empty($password=trim($password)) || mb_strlen($password) < 5 ) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password2 || empty($password2=trim($password2)) || $password != $password2 ) {
            $this->errores['password2'] = 'Los passwords deben coincidir';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::buscaUsuario($nombreUsuario);
            if ($usuario) {
                $this->errores[] = 'El usuario ya existe';		
            } else {
                $usuario = Usuario::crea($nombreUsuario, $nombre, $password, Usuario::USER_ROL);
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $usuario->getNombre();
            }
        }
    }
}
?>
