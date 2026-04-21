<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Inicio';

$urlLlevar = $app->resuelve('/pedidos/realizarPedido.php?pedido=llevar');
$urlLocal  = $app->resuelve('/pedidos/realizarPedido.php?pedido=local');

$contenidoPrincipal = <<<EOS
    <div class="hero" style="background-image: linear-gradient(rgba(255,255,255,0.75), rgba(255,255,255,0.75)), url('{$app->resuelve('/img/bistroFDI_cafeteria.jpg')}'); background-size: cover; background-position: center;">
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

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Bistro FDI'];
$app->generaVista('/plantillas/plantilla.php', $params);