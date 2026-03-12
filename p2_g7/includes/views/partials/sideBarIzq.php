<?php

function mostrarAdministrar() {
    if (isset($_SESSION['login']) && $_SESSION['esAdmin'] === true) {
        return "
            <li><a href='" . RUTA_APP . "/includes/views/pages/admin.php'>Administrar</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/productos.php'>Productos</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/categorias.php'>Categorías</a></li>
        ";
    }
    return "";
}

function mostrarNavegacion() {
    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        return "
            <li><a href='" . RUTA_APP . "/index.php'>Inicio</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/productos.php'>Ver productos</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/categorias.php'>Ver categorías</a></li>
	    	<li><a href='" . RUTA_APP . "/includes/views/pages/crearCategoria.php'>Crear categoría</a></li>
        ";
    } else {
        return "
            <li><a href='" . RUTA_APP . "/index.php'>Inicio</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/registro.php'>Registro</a></li>
        ";
    }
}
?>

<nav id="sidebarIzq">
    <h3>Navegación</h3>
    <ul>
        <?= mostrarNavegacion(); ?>
        <?= mostrarAdministrar(); ?>
    </ul>
</nav>
