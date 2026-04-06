<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<nav id="sidebarIzq">
    <h3>Navegación</h3>
    <ul>

        <!-- Siempre visible -->
        <li>
            <a href="<?= $app->resuelve('/index.php') ?>">Inicio</a>
        </li>

<?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>

        <!-- CLIENTE -->
<?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>

        <li>
            <a href="<?= $app->resuelve('/pedidos/pedido.php') ?>">
                Hacer pedido
            </a>
        </li>

        <li>
            <a href="<?= $app->resuelve('/pedidos/carrito.php') ?>">
                Ver carrito
            </a>
        </li>

<?php endif; ?>


        <!-- COCINERO -->
<?php if (isset($_SESSION['esCocinero']) && $_SESSION['esCocinero'] === true): ?>

        <li>
            <a href="<?= $app->resuelve('/pedidos/cocina.php') ?>">
                Cocina
            </a>
        </li>

<?php endif; ?>


        <!-- GERENTE -->
<?php if (isset($_SESSION['esGerente']) && $_SESSION['esGerente'] === true) : ?>

        <li>
            <a href="<?= $app->resuelve('/pedidos/gerente.php') ?>">
                Gestión de pedidos
            </a>
        </li>

<?php endif; ?>


        <!-- ADMIN -->
<?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true) : ?>

        <li>
            <a href="<?= $app->resuelve('/productos/productos.php') ?>">
                Gestionar productos
            </a>
        </li>

        <li>
            <a href="<?= $app->resuelve('/admin.php') ?>">
                Administración
            </a>
        </li>

<?php endif; ?>


        <!-- Cerrar sesión -->
        <li>
            <a href="<?= $app->resuelve('/logout.php') ?>">
                Cerrar sesión
            </a>
        </li>

<?php else : ?>

        <!-- Usuario no logueado -->

        <li>
            <a href="<?= $app->resuelve('/login.php') ?>">
                Login
            </a>
        </li>

        <li>
            <a href="<?= $app->resuelve('/registro.php') ?>">
                Registro
            </a>
        </li>

<?php endif; ?>

    </ul>
</nav>