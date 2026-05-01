<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\usuarios\Roles;

class FormularioBusquedaUsuarios extends Formulario
{

    public function __construct() {
        parent::__construct('formBusquedaUsuarios', [
            'action' => Aplicacion::getInstance()->resuelve('/usuarios/admin/busquedaUsuarios.php')
        ]);
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
                    <input id="nombre" type="text" name="nombre" placeholder="Buscar por nombre"/>
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

        $app = Aplicacion::getInstance();

        $nombreUsuario = filter_var($datos['nombre'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $urlModificar = $app->resuelve('/usuarios/admin/modificarUsuario.php');

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
            $usuarios = Usuario::buscaUsuario($nombreUsuario);
            if ($usuarios !== null) {
                $filas .= "<tr>
                    <td>{$usuarios->getNombreUsuario()}</td>
                    <td>{$usuarios->getEmail()}</td>
                    <td>{$usuarios->getRol()}</td>
                    <td>{$usuarios->getFechaRegistro()}</td>
                    <td>
                        <form action='{$urlModificar}' method='POST'>
                            <input type='hidden' name='id' value='{$usuarios->getId()}'>
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
            <table class="tabla-general">
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