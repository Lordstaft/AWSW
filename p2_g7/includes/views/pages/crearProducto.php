<?php
require __DIR__ . '/../../config.php';

$categorias = Categoria::listar();

$options = '';
foreach ($categorias as $categoria) {
    $options .= "<option value='{$categoria['id']}'>{$categoria['nombre']}</option>";
}

$tituloPagina = 'Crear producto';

$contenidoPrincipal = <<<EOS
<h1>Crear producto</h1>

<form action="procesarCrearProducto.php" method="POST">
    <fieldset>
        <legend>Nuevo producto</legend>

        <div>
            <label for="nombreProd">Nombre:</label>
            <input id="nombreProd" type="text" name="nombreProd" required />
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea>
        </div>

        <div>
            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id" required>
                $options
            </select>
        </div>

        <div>
            <label for="precio">Precio:</label>
            <input id="precio" type="number" step="0.01" name="precio" required />
        </div>

        <div>
            <label for="iva">IVA:</label>
            <select id="iva" name="iva" required>
                <option value="4">4%</option>
                <option value="10">10%</option>
                <option value="21">21%</option>
            </select>
        </div>

        <div>
            <label for="disponible">Disponible:</label>
            <select id="disponible" name="disponible">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label for="ofertado">Ofertado:</label>
            <select id="ofertado" name="ofertado">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <button type="submit" name="crearProducto">Crear producto</button>
        </div>
    </fieldset>
</form>
EOS;

require __DIR__ . '/plantilla.php';