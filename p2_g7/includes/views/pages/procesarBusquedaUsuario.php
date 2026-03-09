<?php
//Inicio del procesamiento
require __DIR__ . '/../../config.php';

require_once __DIR__. '/../../utils.php';

$filas = '';

$nombreUsuario = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($nombreUsuario === '') {        
    $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $usuarios = Usuario::buscaRolUsuariosAdmin($rol);

    if (!empty($usuarios) && is_array($usuarios)) {
        foreach ($usuarios as $p) {
            $filas .= "<tr>
                <td>{$p->getNombreUsuario()}</td>
                <td>{$p->getEmail()}</td>
                <td>{$p->getRol()}</td>
                <td>{$p->getFechaRegistro()}</td>
                <td>
                    <a href='" . RUTA_APP . "/includes/views/pages/editarUsuario.php?id={$p->getNombreUsuario()}'>Editar</a>
                    <a href='" . RUTA_APP . "/includes/views/pages/eliminarUsuario.php?id={$p->getNombreUsuario()}'>Eliminar</a>
                </td>
            </tr>";
        }
    } else {
        $filas = '<tr><td>No se encontraron usuarios con el rol: ' . $rol . '</td></tr>';
    }
}

else{
    $usuarios = Usuario::buscaNombreUsuariosAdmin($nombreUsuario);

    if (!empty($usuarios)) {
        $filas .= "<tr>
            <td>{$usuarios->getNombreUsuario()}</td>
            <td>{$usuarios->getEmail()}</td>
            <td>{$usuarios->getRol()}</td>
            <td>{$usuarios->getFechaRegistro()}</td>
            <td>
                <a href='" . RUTA_APP . "/includes/views/pages/editarUsuario.php?id={$usuarios->getNombreUsuario()}'>Editar</a>
                <a href='" . RUTA_APP . "/includes/views/pages/eliminarUsuario.php?id={$usuarios->getNombreUsuario()}'>Eliminar</a>
            </td>
        </tr>";
    } else {
        $filas = '<tr><td>No se encontraron usuarios con el nombre: ' . $nombreUsuario . '</td></tr>';
    }
}


$tituloPagina = 'Usuarios';
$contenidoPrincipal = <<<EOS
<h1>Busqueda de usuarios</h1>
<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Fecha de registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        $filas
    </tbody>
</table>
EOS;

require __DIR__ . '/plantilla.php';