<?php
include __DIR__ . '/includes/views/partials/cabecera.php'; 
include __DIR__ . '/includes/views/partials/sideBarIzq.php'; 

$contenido = '';

$pagina = $_GET['pagina'] ?? 'inicio';
        
switch ($pagina) {
    case 'contacto':
        require __DIR__ . '/includes/views/pages/contacto.php';
        break;
    case 'takeAway':
        require __DIR__ . '/includes/views/pages/takeAway.php';
        break;
    case 'eatIn':
        require __DIR__ . '/includes/views/pages/eatIn.php';
        break;
    default:
        require __DIR__ . '/includes/views/pages/plantilla.php';
        }
?>
    
<main>
    <article>
        <?php echo $contenido; ?>
    </article>
</main>

<?php 
include __DIR__ . '/includes/views/partials/sideBarDer.php'; 
include __DIR__ . '/includes/views/partials/pie.php'; 
?>
