<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\formularios\FormularioEditarCategoria;

$id = $_GET['id'] ?? null;

if (!$id) {
    die('Categoría no válida');
}

$form = new FormularioEditarCategoria($id);
$htmlFormulario = $form->gestiona();

$tituloPagina = 'Editar categoría';
$contenidoPrincipal = <<<EOS
<h1>Editar categoría</h1>
$htmlFormulario
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';