<?php
require __DIR__ . '/../../config.php';

$tituloPagina = 'Crear categoría';

$contenidoPrincipal = <<<EOS
<h1>Crear categoría</h1>

<form action="procesarCrearCategoria.php" method="POST">
    <fieldset>
        <legend>Nueva categoría</legend>

        <div>
            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" required />
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea>
        </div>

        <div>
            <label for="imgCategoriaProd">Imagen:</label>
            <input id="imgCategoriaProd" type="text" name="imgCategoriaProd" placeholder="ej: bebidas.jpg" />
        </div>

        <div>
            <button type="submit" name="crearCategoria">Crear categoría</button>
        </div>
    </fieldset>
</form>
EOS;

require __DIR__ . '/plantilla.php';