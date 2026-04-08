<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<nav id="sidebarIzq">
    <h3>Navegación</h3>
    <ul>

        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>


        <!-- CLIENTE -->
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
                
                <li>
                    <a href="<?= $app->resuelve('/inicio.php') ?>">Inicio</a>
                </li>
                
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
                    <a href="<?= $app->resuelve('/usuarios/cocinero/pedidosPendientes.php') ?>">
                        Pedidos sin asignar
                    </a>
                </li>

                <li>
                    <a href="<?= $app->resuelve('/usuarios/cocinero/pedidos.php') ?>">
                        Pedidos sin preparar
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

                <li>
                    <a href="<?= $app->resuelve('/usuarios/gerente/productos.php') ?>">
                        Gestionar productos
                    </a>
                </li>

                <li>
                    <a href="<?= $app->resuelve('/usuarios/gerente/registroProducto.php') ?>">
                        Registrar producto
                    </a>
                </li>

        <?php endif; ?>


        <!-- ADMIN -->
        <?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true) : ?>

                <li>
                    <a href="<?= $app->resuelve('/usuarios/admin/categorias.php') ?>">
                        Gestionar categorías
                    </a>
                </li>

                <li>
                    <a href="<?= $app->resuelve('/usuarios/admin/registroCategoria.php') ?>">
                        Registrar categoría
                    </a>
                </li>

                <li>
                    <a href="<?= $app->resuelve('/registro.php') ?>">
                        Añadir usuario
                    </a>
                </li>

                <li>
                    <a href="<?= $app->resuelve('/usuarios/admin.php') ?>">
                        Administración
                    </a>
                </li>

        <?php endif; ?>

        <?php else : ?>

        <!-- Usuario no logueado -->

        <li>
            <a href="<?= $app->resuelve('/index.php') ?>">
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
