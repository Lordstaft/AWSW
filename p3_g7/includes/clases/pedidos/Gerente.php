<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/clases/pedidos/Pedido.php';

use es\ucm\fdi\aw\pedidos\Pedido;

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

$pedidos = Pedido::getPedidosPendientes();

$tituloPagina = "Pedidos - Gerente";

$contenidoPrincipal = <<<EOS
<h1>Pedidos pendientes</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Estado</th>
        <th>Cocinero</th>
    </tr>
EOS;

if (!$pedidos || count($pedidos) == 0) {
    $contenidoPrincipal .= "<tr><td colspan='4'>No hay pedidos</td></tr>";
}
else {
    foreach ($pedidos as $pedido) {
        $id = $pedido['id'];
        $usuario = $pedido['usuario'];
        $estado = $pedido['estado'];


        $cocinero = $pedido['cocinero'];
        $avatarCocinero = $pedido['avatarCocinero'] ?? null;

        if (!$cocinero) {
            $htmlCocinero = "-";
        } else {
            $rutaAvatar = $app->resuelve('/img/' . ($avatarCocinero ?? 'usuario_default.png'));

            $htmlCocinero = "
                <div class='cocinero-info'>
                    <img src='{$rutaAvatar}' class='avatar-cocinero' alt='avatar cocinero'>
                    <span>{$cocinero}</span>
                </div>
            ";
        }

        $contenidoPrincipal .= <<<EOS
        <tr>
            <td>$id</td>
            <td>$usuario</td>
            <td>$estado</td>
            <td>$htmlCocinero</td>
        </tr>
EOS;
    }
}

$contenidoPrincipal .= "</table>";

require __DIR__.'/../includes/vistas/plantillas/plantilla.php';