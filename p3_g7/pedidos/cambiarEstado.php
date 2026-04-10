<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Roles;

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

/* Control de acceso */
if (!$app->usuarioLogueado()) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

if (!$app->tieneRol(Roles::GERENTE) && !$app->tieneRol(Roles::ADMIN)) {
    header('Location: '.$app->resuelve('/index.php'));
    exit();
}

/* Datos */
$id = (int) ($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

/* Estados válidos */
$estadosValidos = [
    'recibido',
    'en_preparacion',
    'cocinando',
    'listo_cocina',
    'terminado',
    'entregado',
    'cancelado'
];

/* Validación */
if ($id > 0 && in_array($estado, $estadosValidos)) {

    $query = sprintf(
        "UPDATE pedidos SET estadoPedido='%s' WHERE idPedido=%d",
        $conn->real_escape_string($estado),
        $id
    );

    $conn->query($query);
}

/* Redirección */
header('Location: '.$app->resuelve('/pedidos/gerente.php'));
exit();