<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

function mostrarSaludo()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if (isset($_SESSION['login'], $_SESSION['nombreUsuario']) && ($_SESSION['login']===true)) {
        $nombreUsuario = $_SESSION['nombreUsuario'];

        $formLogout = new FormularioLogout();
        $htmlLogout = $formLogout->gestiona();
        $html = "Bienvenido, {$nombreUsuario}. $htmlLogout";
    } 
    else {
        $loginUrl = $app->resuelve('/index.php');
        $registroUrl = $app->resuelve('/registro.php');
        $html = <<<EOS
        Usuario desconocido. <a href="{$loginUrl}">Login</a> <a href="{$registroUrl}">Registro</a>
      EOS;
    }

    return $html;
}
?>

<header>
    <h1><?= $params['cabecera'] ?? 'BistroFDI' ?></h1>
    <div class="saludo">
        <?= mostrarSaludo(); ?>
    </div>
</header>