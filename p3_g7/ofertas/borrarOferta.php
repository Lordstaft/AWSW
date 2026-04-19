<?php
require_once __DIR__ . '/../includes/config.php';
 
use es\ucm\fdi\aw\ofertas\Oferta;
use es\ucm\fdi\aw\Aplicacion;
 
$app = Aplicacion::getInstance();
$idOferta = $_GET['id'] ?? 0;
$idOferta = (int)$idOferta;
 
if ($idOferta > 0) {
    $oferta = Oferta::buscaOferta($idOferta);
 
    if ($oferta) {
        Oferta::eliminarOferta($idOferta);
    }
}
 
header('Location: ' . $app->resuelve('/usuarios/gerente/ofertas.php'));
exit();