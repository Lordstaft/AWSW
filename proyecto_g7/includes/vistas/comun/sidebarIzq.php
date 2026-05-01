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
        <li class="nav-item">
            <a href="<?= $app->resuelve('/index.php') ?>">Inicio</a>
            <span class="tooltip">Ve a la página principal</span>
        </li>
        <li class="nav-item">
            <a href="<?= $app->resuelve('/pedidos/carrito.php') ?>">Ver carrito</a>
            <span class="tooltip">Revisa tus productos seleccionados</span>
        </li>
        <li class="nav-item">
            <a href="<?= $app->resuelve('/usuarios/perfil.php') ?>">Perfil</a>
            <span class="tooltip">Gestiona tu cuenta y datos personales, accede a la lista de pedidos </span>
        </li>

        <!-- COCINERO -->
        <?php if (isset($_SESSION['esCocinero']) && $_SESSION['esCocinero'] === true): ?>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/cocinero/pedidosPendientes.php') ?>">Pedidos sin asignar</a>
                 <span class="tooltip">Ver pedidos que aún no tienen cocinero asignado</span>
            </li>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/cocinero/pedidos.php') ?>">Pedidos sin preparar</a>
                <span class="tooltip">Ver tus pedidos asignados pendientes de preparar</span>
            </li>
        <?php endif; ?>

        <!-- CAMARERO -->
        <?php if (isset($_SESSION['esCamarero']) && $_SESSION['esCamarero'] === true) : ?>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/camarero/gestionarEntregas.php') ?>">Gestionar entregas</a>
                <span class="tooltip">Gestiona las entregas de pedidos a los clientes</span>
            </li>
        <?php endif; ?>

        <!-- GERENTE -->
        <?php if (isset($_SESSION['esGerente']) && $_SESSION['esGerente'] === true) : ?>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/gerente/pedidosAsignados.php') ?>">Gestión de pedidos</a>
                <span class="tooltip">Supervisa y gestiona todos los pedidos asignados</span>
            </li>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/gerente/productos.php') ?>">Gestionar productos</a>
                <span class="tooltip">Edita o elimina los productos del menú</span>
            </li>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/gerente/registroProducto.php') ?>">Registrar producto</a>
                <span class="tooltip">Añade un nuevo producto al menú</span>
            </li>
            <li class="nav-item">
                <a href="<?= $app->resuelve('/usuarios/gerente/ofertas.php') ?>">Gestionar ofertas</a>
                <span class="tooltip">Crea, edita o elimina ofertas disponibles</span>
            </li>
        <?php endif; ?>

        <?php else : ?>
        <!-- Usuario no logueado -->
        <li class="nav-item">
            <a href="<?= $app->resuelve('/login.php') ?>">Login</a>
            <span class="tooltip">Inicia sesión en tu cuenta</span>
        </li>
        <li class="nav-item">
            <a href="<?= $app->resuelve('/registro.php') ?>">Registro</a>
            <span class="tooltip">Crea una cuenta nueva</span>
        </li>
        <?php endif; ?>

    </ul>
</nav>