<?php
include __DIR__ . '/../../config.php';

$_SESSION['pedido'] = $_GET['pagina'];

$productoPrincipal = '';

$tituloPagina = "Pedido";

$contenidoPrincipal = <<<EOS
        <h2>Productos</h2>
        <p>{$_SESSION['pedido']}</p>
        <ul>
            <li><a href="index.php?producto=bebidas">Bebidas</a></li>
            <li><a href="index.php?producto=bocadillos">Bocadillos</a></li>
            <li><a href="index.php?producto=ensaladas">Ensaladas</a></li>
            <li><a href="index.php?producto=platosCalientes">Platos Calientes</a></li>
            <li><a href="index.php?producto=postres">Postres</a></li>
            <li><a href="index.php?producto=snacks">Snacks</a></li>
        </ul>
     $productoPrincipal

EOS;

require __DIR__ . '/plantilla.php';
