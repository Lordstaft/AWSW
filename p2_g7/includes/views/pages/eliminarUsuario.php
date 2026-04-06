<?php
//Inicio del procesamiento
require __DIR__ . '/../../config.php';

$tituloPagina = 'Editar usuario';

$dato = $_GET['id'];

$roles = '';

$usuario = Usuario::buscaUsuario($dato);

foreach (Roles::cases() as $rol) {
    if ($usuario->getRol() === $rol->value) {
        $roles .= "<option value='{$rol->value}' selected>{$rol->value}</option>";
    } else {
        $roles .= "<option value='{$rol->value}'>{$rol->value}</option>";
    }
}

$contenidoPrincipal = <<<EOS
<h1>Editar usuario</h1>
<form action="/procesarEliminarUsuario" method="POST">
    <fieldset>
        <legend>Usuario</legend>

        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" value = "{$usuario->getNombre()}" />
        </div>

        <div>
            <label for="apellidos">Apellidos:</label>
            <input id="apellidos" type="text" name="apellidos" value = "{$usuario->getApellidos()}" />
        </div>

        <div>
            <label for="nombreUsuario">Nombre de usuario:</label>
            <input id="nombreUsuario" type="text" name="nombreUsuario" value = "{$usuario->getNombreUsuario()}" />
        </div>

        <div>
            <label for="email">Email:</label>
            <input id="email" type="email" name="email" value = "{$usuario->getEmail()}" />
        </div>

        <div>
            <label for="rol">Rol:</label>
                <select id="rol" name="rol">
                $roles
            </select>
        </div>

        <div>
            <button type="submit" name="eliminarUsuario">Eliminar</button>
        </div>
    </fieldset>
</form>
EOS;

require __DIR__ . '/plantilla.php';