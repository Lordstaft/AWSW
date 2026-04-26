<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\Pedido;
use es\ucm\fdi\aw\usuarios\Roles;

if (!$app->usuarioLogueado()) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

if (!$app->tieneRol(Roles::GERENTE) && !$app->tieneRol(Roles::ADMIN)) {
    $tituloPagina = 'Pedidos gerente';
    $contenidoPrincipal = '<p>No tienes permisos para acceder a esta página.</p>';

    $params = [
        'tituloPagina' => $tituloPagina,
        'contenidoPrincipal' => $contenidoPrincipal
    ];

    $app->generaVista('/plantillas/plantilla.php', $params);
    exit();
}

$tituloPagina = 'Pedidos gerente';

$pedidos = Pedido::getPedidosPendientes();

$contenidoPrincipal = '<h1>Pedidos pendientes</h1>';

if (!$pedidos || count($pedidos) == 0) {
    $contenidoPrincipal .= '<p>No hay pedidos.</p>';
}
else {
    $contenidoPrincipal .= '<table border="1">';
    $contenidoPrincipal .= '<tr><th>ID</th><th>Usuario</th><th>Estado</th><th>Cocinero</th></tr>';

    foreach ($pedidos as $pedido) {
        $id = $pedido['id'];
        $usuario = $pedido['usuario'];
        $estado = $pedido['estado'];
        $cocinero = $pedido['cocinero'];
        $avatarCocinero = $pedido['avatarCocinero'] ?? null;

        if (!$cocinero) {
            $htmlCocinero = '-';
        }
        else {
            $rutaAvatar = $app->resuelve('/img/' . ($avatarCocinero ?? 'usuario_default.png'));

            $htmlCocinero = "
                <div class='cocinero-info'>
                    <img src='{$rutaAvatar}' class='avatar-cocinero' alt='avatar cocinero'>
                    <span>{$cocinero}</span>
                </div>
            ";
        }

        $contenidoPrincipal .= "
            <tr>
                <td>$id</td>
                <td>$usuario</td>
                <td>$estado</td>
                <td>$htmlCocinero</td>
            </tr>
        ";
    }

    $contenidoPrincipal .= '</table>';
}

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal
];

$app->generaVista('/plantillas/plantilla.php', $params);