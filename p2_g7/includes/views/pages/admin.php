<?php
require __DIR__ . '/../../config.php';

$tituloPagina = 'Buscar usuario';

$contenidoPrincipal = <<<EOS
<h1>Buscar Usuario</h1>

<form action="procesarBusquedaUsuario.php" method="POST">
    <fieldset>
        <legend>Buscar usuario</legend>

        <div>
            <label for="nombre">Buscar</label>
            <input id="nombre" type="text" name="nombre" placeholder="Buscar por nombre o email"/>
        </div>

        <div>
            <label for="rol">Rol</label>
            <select id="rol" name="rol">
                <option value="" selected>Todos</option>
                <option value="cocinero">cocinero</option>
                <option value="cliente">cliente</option>
                <option value="camarero">camarero</option>
                <option value="gerente">gerente</option>
            </select>
        </div>

        <div>
            <button type="submit" name="buscarUsuario">Buscar usuario</button>
        </div>
    </fieldset>
</form>
EOS;

require __DIR__ . '/plantilla.php';