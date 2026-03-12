<?php
//Inicio del procesamiento
require __DIR__ . '/../../config.php';

require_once __DIR__. '/../../utils.php';

$tituloPagina = 'Busqueda de usuarios';

$contenidoPrincipal = <<<EOS
    {$_SESSION['resultadosBusqueda']}
    EOS;

require __DIR__ . '/plantilla.php';