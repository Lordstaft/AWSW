<?php
if (!empty($params['ocultarNav'])) return;
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<nav id="sidebarIzq">
    <h2>Navegación</h2>
    <ul>

        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>

        <!-- CLIENTE -->
        <li>
            <a href="<?= $app->resuelve('/inicio.php') ?>">Inicio</a>
        </li>
        <li>
            <a href="<?= $app->resuelve('/pedidos/carrito.php') ?>">Ver carrito</a>
        </li>
        <li>
            <a href="<?= $app->resuelve('/usuarios/perfil.php') ?>">Perfil</a>
        </li>

        <!-- COCINERO -->
        <?php if (isset($_SESSION['esCocinero']) && $_SESSION['esCocinero'] === true): ?>
            <li>
                <a href="<?= $app->resuelve('/usuarios/cocinero/pedidosPendientes.php') ?>">Pedidos sin asignar</a>
            </li>
            <li>
                <a href="<?= $app->resuelve('/usuarios/cocinero/pedidos.php') ?>">Pedidos sin preparar</a>
            </li>
        <?php endif; ?>

        <!-- GERENTE -->
        <?php if (isset($_SESSION['esGerente']) && $_SESSION['esGerente'] === true) : ?>
            <li>
                <a href="<?= $app->resuelve('/usuarios/gerente/pedidosAsignados.php') ?>">Gestión de pedidos</a>
            </li>
            <li>
                <a href="<?= $app->resuelve('/usuarios/gerente/productos.php') ?>">Gestionar productos</a>
            </li>
            <li>
                <a href="<?= $app->resuelve('/usuarios/gerente/registroProducto.php') ?>">Registrar producto</a>
            </li>
            <li>
                <a href="<?= $app->resuelve('/usuarios/gerente/ofertas.php') ?>">Gestionar ofertas</a>
            </li>
        <?php endif; ?>

        <!-- CAMARERO -->
        <?php if (isset($_SESSION['esCamarero']) && $_SESSION['esCamarero'] === true) : ?>
            <li>
                <a href="<?= $app->resuelve('/usuarios/camarero/gestionarEntregas.php') ?>">Gestionar entregas</a>
            </li>
        <?php endif; ?>

        <?php else : ?>

        <!-- Usuario no logueado -->
        <li>
            <a href="<?= $app->resuelve('/login.php') ?>">Login</a>
        </li>
        <li>
            <a href="<?= $app->resuelve('/registro.php') ?>">Registro</a>
        </li>

        <?php endif; ?>

    </ul>
</nav>