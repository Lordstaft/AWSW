<?php
require_once __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\forms\FormularioEditarProducto;

$id = $_GET['id'] ?? null;

if (!$id) {
    die('Producto no válido');
}

$form = new FormularioEditarProducto($id);
$htmlFormulario = $form->gestiona();

$tituloPagina = 'Editar producto';
$contenidoPrincipal = <<<EOS
<h1>Editar producto</h1>
$htmlFormulario
EOS;

require __DIR__.'/plantilla.php';