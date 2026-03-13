<?php

function mostrarCrearUsuario() {
    $mostrar = '';
    if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true) {
        $mostrar .= "
            <li><a href='" . RUTA_APP . "/includes/views/pages/registro.php'>Crear Usuario</a></li>
        ";
    }

    $mostrar .= "
        <li><a href='" . RUTA_APP . "/includes/views/pages/usuario.php'>Perfil</a></li>
    ";
    
    return $mostrar;
}
?>

<nav id="sidebarDer">
    <ul>
        <?= mostrarCrearUsuario(); ?>
    </ul>
</nav>