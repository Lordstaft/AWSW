<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

function mostrarSaludo($app, $ocultarNav = false)
{
    $html = '';
    if (isset($_SESSION['login'], $_SESSION['nombreUsuario']) && ($_SESSION['login'] === true)) {
        $nombreUsuario = $_SESSION['nombreUsuario'];

        $formLogout = new FormularioLogout();
        $htmlLogout = $formLogout->gestiona();
        $html = "Bienvenido, {$nombreUsuario}. $htmlLogout";
    } 
    else {
    // Solo mostrar login/registro en el header si NO estamos ocultando la nav
        if ($ocultarNav) {
            $html = 'Usuario desconocido.';
        } else {
            $loginUrl = $app->resuelve('/index.php');
            $registroUrl = $app->resuelve('/registro.php');
            $html = <<<EOS
            Usuario desconocido. <a href="{$loginUrl}">Login</a> <a href="{$registroUrl}">Registro</a>
            EOS;
        }
}

    return $html;
}
$app = Aplicacion::getInstance();
$ocultarNav = isset($params['ocultarNav']) ? $params['ocultarNav'] : false;
?>

<header>
    <div class="cabecera-contenido">
        <img src="<?= $app->resuelve('/img/logo.png') ?>" alt="Logo BistroFDI" class="logo">
        <h1><?= $params['cabecera'] ?? 'BistroFDI' ?></h1>
    </div>
    <div class="saludo">
        <?= mostrarSaludo($app, $ocultarNav); ?>
    </div>
     
</header>
