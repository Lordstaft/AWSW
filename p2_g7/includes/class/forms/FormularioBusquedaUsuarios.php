<?php
require_once __DIR__ . '/Formulario.php';
require_once __DIR__ . '/../Usuario.php';
require_once __DIR__ . '/../Roles.php';

class FormularioBusquedaUsuarios extends Formulario
{

    public function __construct() {
        parent::__construct('formBusquedaUsuarios', ['urlRedireccion' => 'busquedaUsuarios.php']);
    }
    
    protected function generaCamposFormulario(&$datos){

        $roles = '';
        $roles .= "<option value='' selected>Todos</option>";

        foreach (Roles::cases() as $rol) {
            if ($rol->value !== Roles::ADMIN->value) {
                $roles .= "<option value='{$rol->value}'>{$rol->value}</option>";
            } 
        }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Buscar usuario</legend>

                <div>
                    <label for="nombre">Buscar</label>
                    <input id="nombre" type="text" name="nombre" placeholder="Buscar por nombre o email"/>
                </div>

                <div>
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol">
                        $roles
                    </select>
                </div>

                <div>
                    <button type="submit" name="buscarUsuario">Buscar usuario</button>
                </div>
            </fieldset>
        EOF;
        return $html;

    }

    protected function procesaFormulario(&$datos)
    {
        $filas = '';
        $this->errores = [];

        $nombreUsuario = filter_var($datos['nombre'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($nombreUsuario === '') {        
            $rol = filter_var($datos['rol'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $usuarios = Usuario::buscaRolUsuariosAdmin($rol);

            if (!empty($usuarios) && is_array($usuarios)) {
                foreach ($usuarios as $p) {
                    $filas .= "<tr>
                        <td>{$p->getNombreUsuario()}</td>
                        <td>{$p->getEmail()}</td>
                        <td>{$p->getRol()}</td>
                        <td>{$p->getFechaRegistro()}</td>
                        <td>
                            <a href='" . RUTA_APP . "/includes/views/pages/editarUsuario.php?id={$p->getNombreUsuario()}'>Editar</a>
                            <a href='" . RUTA_APP . "/includes/views/pages/eliminarUsuario.php?id={$p->getNombreUsuario()}'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } 
            else {
                $filas = null;
            }
        }

        else{
            $usuarios = Usuario::buscaUsuario($nombreUsuario);
            if ($usuarios !== null) {
                $filas .= "<tr>
                    <td>{$usuarios->getNombreUsuario()}</td>
                    <td>{$usuarios->getEmail()}</td>
                    <td>{$usuarios->getRol()}</td>
                    <td>{$usuarios->getFechaRegistro()}</td>
                    <td>
                        <a href='" . RUTA_APP . "/includes/views/pages/editarUsuario.php?id={$usuarios->getNombreUsuario()}'>Editar</a>
                        <a href='" . RUTA_APP . "/includes/views/pages/eliminarUsuario.php?id={$usuarios->getNombreUsuario()}'>Eliminar</a>
                    </td>
                </tr>";
            } 
            else {
                $filas = null;
            }
        }

        if ($filas !== null) {
            $_SESSION['resultadosBusqueda'] = <<<EOS
            <h1>Busqueda de usuarios</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de registro</th>
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
