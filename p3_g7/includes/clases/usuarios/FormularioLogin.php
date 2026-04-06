<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioLogin extends Formulario
{

    public function __construct() {
        parent::__construct('formLogin', [
            'action' => Aplicacion::getInstance()->resuelve('index.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('inicio.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $password = $datos['password'] ?? '';

        $html = <<<EOS
        <fieldset>
            <legend>Login</legend>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" value="$password" />
            </div>
            <div>
                <button type="submit" name="login">Entrar</button>
            </div>
        </fieldset>
        EOS;

        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $password = trim($datos['password'] ?? '');

        if ($nombreUsuario === '') {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }

        if ($password === '') {
            $this->errores['password'] = 'La contraseña no puede estar vacía';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::login($nombreUsuario, $password);
        
            if (!$usuario) {
                $this->errores[] = "El usuario o el password no coinciden";
            } 
            else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $usuario->getNombre();
                $_SESSION['nombreUsuario'] = $usuario->getNombreUsuario();
                if($usuario->getRol() === Roles::ADMIN->value){
                    $_SESSION['esAdmin'] = true;
                    $_SESSION['esGerente'] = true;
                    $_SESSION['esCamarero'] = true;
                    $_SESSION['esCocinero'] = true;
                }
                
                elseif($usuario->getRol() === Roles::GERENTE->value){
                    $_SESSION['esGerente'] = true;
                    $_SESSION['esCamarero'] = true;
                    $_SESSION['esCocinero'] = true;
                }

                elseif($usuario->getRol() === Roles::CAMARERO->value){
                    $_SESSION['esCamarero'] = true;
                }

                elseif($usuario->getRol() === Roles::COCINERO->value){
                    $_SESSION['esCocinero'] = true;
                }

                else{
                    $_SESSION['esAdmin'] = false;
                    $_SESSION['esGerente'] = false;
                    $_SESSION['esCamarero'] = false;
                    $_SESSION['esCocinero'] = false;
                }
            }
        }
    }
}
