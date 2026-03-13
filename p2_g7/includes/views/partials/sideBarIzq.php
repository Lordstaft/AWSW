<?php

function mostrarNavegacion() {
    $mostrar = '';
    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        $mostrar .= "
            <li><a href='" . RUTA_APP . "/includes/views/pages/productos.php'>Ver productos</a></li>
            <li><a href='" . RUTA_APP . "/includes/views/pages/categorias.php'>Ver categorías</a></li>
        ";
    }

    if(isset($_SESSION['login']) && $_SESSION['esGerente'] === true){
        $mostrar .= "
        <li><a href='" . RUTA_APP . "/includes/views/pages/crearCategoria.php'>Crear categoría</a></li>
        <li><a href='" . RUTA_APP . "/includes/views/pages/pedidosGerente.php'>Ver pedidos</a></li>
        ";
    }

    if (isset($_SESSION['login']) && $_SESSION['esAdmin'] === true) {
        $mostrar .= "
            <li><a href='" . RUTA_APP . "/includes/views/pages/admin.php'>Administrar</a></li>
        ";
    }
    
    if(isset($_SESSION['login']) === false) {
        $mostrar .= "
            <li><a href='" . RUTA_APP . "/includes/views/pages/registro.php'>Registro</a></li>
        ";
    }

    else{
        $mostrar .= "
            <li><a href='" . RUTA_APP . "/index.php'>Inicio</a></li>
        ";
    }

    return $mostrar;
}
?>

<nav id="sidebarIzq">
    <h3>Navegación</h3>
    <ul>
        <?= mostrarNavegacion(); ?>
    </ul>
</nav>
