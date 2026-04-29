<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<aside id="sidebarDer">

    <!-- ADMIN -->
    <?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true) : ?>

        <ul>
            <li>
                <a href="<?= $app->resuelve('/usuarios/admin.php') ?>">
                    Administrar usuarios
                </a>
            </li>

            <li>
                <a href="<?= $app->resuelve('/registro.php') ?>">
                    Registrar usuario
                </a>
            </li>

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
        </ul>

    <?php endif; ?>

</aside>
