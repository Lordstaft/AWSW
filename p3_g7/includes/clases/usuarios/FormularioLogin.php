<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Formulario;

class FormularioLogin extends Formulario
{
    public function __construct() {
        parent::__construct('formLogin');
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
                $_SESSION['idUsuario'] = $usuario->getId();
                $_SESSION['nombre'] = $usuario->getNombre();
                $_SESSION['nombreUsuario'] = $usuario->getNombreUsuario();
                $_SESSION['rol'] = $usuario->getRol();
                $_SESSION['roles'] = [$usuario->getRol()];

                if ($usuario->getRol() === Roles::ADMIN) {
                    $_SESSION['esAdmin'] = true;
                    $_SESSION['esGerente'] = true;
                }
                elseif ($usuario->getRol() === Roles::GERENTE) {
                    $_SESSION['esAdmin'] = false;
                    $_SESSION['esGerente'] = true;
                }
                else {
                    $_SESSION['esAdmin'] = false;
                    $_SESSION['esGerente'] = false;
                }
            }
        }
    }
}
