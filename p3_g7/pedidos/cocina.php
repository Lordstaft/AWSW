<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\pedidos\FormularioCocina;
use es\ucm\fdi\aw\usuarios\Roles;

if (!$app->usuarioLogueado()) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

if (!$app->tieneRol(Roles::COCINERO) && !$app->tieneRol(Roles::ADMIN)) {
    $tituloPagina = 'Cocina';
    $contenidoPrincipal = '<p>No tienes permisos para acceder a esta página.</p>';

    $params = [
        'tituloPagina' => $tituloPagina,
        'contenidoPrincipal' => $contenidoPrincipal
    ];

    $app->generaVista('/plantillas/plantilla.php', $params);
    exit();
}

$tituloPagina = 'Cocina';

$form = new FormularioCocina();
$contenidoPrincipal = $form->gestiona();

$params = [
    'tituloPagina' => $tituloPagina,
    'contenidoPrincipal' => $contenidoPrincipal
];

$app->generaVista('/plantillas/plantilla.php', $params);