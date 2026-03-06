<?php
require __DIR__ . '/../../config.php';

$categorias = Categoria::listar();

$filas = '';

foreach ($categorias as $categoria) {

	if (!empty($categoria['imgCategoriaProd'])) {
		 $nombreImagen = trim($categoria['imgCategoriaProd']);
		$rutaImagen = RUTA_IMGS . '/' . $nombreImagen;
		$imagen = "<img src=\"$rutaImagen\" alt=\"{$categoria['nombre']}\" width=\"100\">";
	} else {
		$imagen = 'Sin imagen';
	}
    $descripcion = $categoria['descripcion'] ? $categoria['descripcion'] : 'Sin descripción';

    $filas .= "<tr>
        <td>{$categoria['id']}</td>
        <td>{$categoria['nombre']}</td>
        <td>{$descripcion}</td>
        <td>{$imagen}</td>
    </tr>";
}

$tituloPagina = 'Categorías';

$contenidoPrincipal = <<<EOS
<h1>Listado de categorías</h1>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
        </tr>
    </thead>
    <tbody>
        $filas
    </tbody>
</table>
EOS;

require __DIR__ . '/plantilla.php';
