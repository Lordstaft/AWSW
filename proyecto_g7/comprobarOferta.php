<?php
require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\ofertas\Oferta;

$productosInput = $_POST['productos'] ?? [];

if (empty($productosInput)) {
    echo "disponible";
    exit;
}


$productosInput = array_map('intval', $productosInput);
ksort($productosInput);

$ofertaIdExcluir = isset($_POST['ofertaId']) ? intval($_POST['ofertaId']) : 0;
$ofertas = Oferta::listarOfertas();

$existe = false;

if ($ofertas) {
    foreach ($ofertas as $oferta) {

        if ($ofertaIdExcluir > 0 && $oferta->getIdOferta() === $ofertaIdExcluir) {
            continue;
        }

        $productosOferta = $oferta->getProductos();

        $comparar = [];

        foreach ($productosOferta as $p) {
            $comparar[(int)$p['producto_id']] = (int)$p['cantidad'];
        }

        ksort($comparar);

        if ($comparar === $productosInput) {
            $existe = true;
            break;
        }
    }
}

echo $existe ? "existe" : "disponible";