<?php
require_once __DIR__.'/includes/config.php';
$app->redirige($app->resuelve('/index.php'));

<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Inicio';

$urlLogin    = $app->resuelve('/login.php');
$urlRegistro = $app->resuelve('/registro.php');
$urlLlevar   = $app->resuelve('/pedidos/realizarPedido.php?pedido=llevar');
$urlLocal    = $app->resuelve('/pedidos/realizarPedido.php?pedido=local');

$imagenFondo = $app->resuelve('/img/bistroFDI_cafeteria.jpg');

$ocultarNav = !isset($_SESSION['login']) || !$_SESSION['login'];

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    // Usuario NO autenticado
    $contenidoPrincipal = <<<EOS
    <div class="hero" style="background-image: linear-gradient(rgba(255,255,255,0.75), rgba(255,255,255,0.75)), url('$imagenFondo'); background-size: cover; background-position: center;">
        <div class="hero-texto">
            <h2>Bienvenido a Bistro FDI</h2>
            <p>Cocina fresca, pedidos rápidos. Identifícate para realizar tu pedido.</p>
        </div>
        <div class="opciones-pedido">
            <a href="$urlLogin" class="opcion-pedido">
                <span class="opcion-icono">🔑</span>
                <span class="opcion-titulo">Iniciar sesión</span>
                <span class="opcion-desc">¿Ya tienes cuenta? Entra aquí</span>
            </a>
            <a href="$urlRegistro" class="opcion-pedido">
                <span class="opcion-icono">📝</span>
                <span class="opcion-titulo">Registrarse</span>
                <span class="opcion-desc">Crea tu cuenta gratis</span>
            </a>
        </div>
    </div>
EOS;
} else {
    // Usuario autenticado
    $contenidoPrincipal = <<<EOS
    <div class="hero" style="background-image: linear-gradient(rgba(255,255,255,0.75), rgba(255,255,255,0.75)), url('$imagenFondo'); background-size: cover; background-position: center;">
        <div class="hero-texto">
            <h2>Bienvenido a Bistro FDI</h2>
            <p>Cocina fresca, pedidos rápidos. ¿Cómo quieres tu pedido hoy?</p>
        </div>
        <div class="opciones-pedido">
            <a href="$urlLlevar" class="opcion-pedido">
                <span class="opcion-icono">🛵</span>
                <span class="opcion-titulo">Pedido para llevar</span>
                <span class="opcion-desc">Recógelo cuando esté listo</span>
            </a>
            <a href="$urlLocal" class="opcion-pedido">
                <span class="opcion-icono">🍽️</span>
                <span class="opcion-titulo">Consumir en local</span>
                <span class="opcion-desc">Disfrútalo aquí con nosotros</span>
            </a>
        </div>
    </div>
EOS;
}

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal,
    'cabecera' => 'Bistro FDI',
    'ocultarNav' => $ocultarNav
];
$app->generaVista('/plantillas/plantilla.php', $params);
