<?php
namespace es\ucm\fdi\aw\forms;
use es\ucm\fdi\aw\forms\Formulario;
use es\ucm\fdi\aw\Usuario;
use es\ucm\fdi\aw\Roles;

class FormularioEditarUsuario extends Formulario
{
    public function __construct() {
        parent::__construct('formEditarUsuario', ['urlRedireccion' => 'admin.php']);
    }
    
    protected function generaCamposFormulario(&$datos){
        $busqueda = $_GET['id'];
        $usuario = Usuario::buscaUsuario($busqueda);
        $roles = '';
        foreach (Roles::cases() as $rol) {
            if ($usuario->getRol() === $rol->value) {
                $roles .= "<option value='{$rol->value}' selected>{$rol->value}</option>";
            } else {
                $roles .= "<option value='{$rol->value}'>{$rol->value}</option>";
            }
        }
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'apellidos', 'email'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
            <fieldset>
                <legend>Usuario</legend>
                
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
            
            if (count($this->errores) === 0) {
                $modificacion = Usuario::editarUsuario($id, $nombreUsuario, $nombre, $apellidos, $email, $rol);
            }
        }

        elseif(isset($datos['eliminarUsuario'])){
            $id = $datos['id'];
            Usuario::eliminarUsuario($id);
        }
    }
}
