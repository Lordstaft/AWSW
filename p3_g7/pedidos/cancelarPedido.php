<?php
require_once __DIR__ . '/../includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Roles;

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

if (!$app->usuarioLogueado()) {
    header('Location: '.$app->resuelve('/login.php'));
    exit();
}

if (!$app->tieneRol(Roles::GERENTE) && !$app->tieneRol(Roles::ADMIN)) {
    header('Location: '.$app->resuelve('/index.php'));
    exit();
}

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $query = sprintf(
        "UPDATE pedidos SET estado='cancelado' WHERE id=%d",
        $id
    );

    $conn->query($query);
}

header('Location: '.$app->resuelve('/pedidos/gerente.php'));
exit();