<?php

function mostrarCrearUsuario() {
    if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'] === true) {
        return "
            <li><a href='" . RUTA_APP . "/includes/views/pages/registro.php'>Crear Usuario</a></li>
        ";
    }
    return "";
}

?>

<nav id="sidebarDer">
    <ul>
        <?= mostrarCrearUsuario(); ?>
    </ul>
</nav>