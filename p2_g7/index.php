<?php
include __DIR__ . '/includes/views/partials/cabecera.php'; 
include __DIR__ . '/includes/views/partials/sideBarIzq.php'; 

$contenido = '';

$pagina = $_GET['pagina'] ?? 'inicio';
        
switch ($pagina) {

    // --- Generales ---
    case 'inicio':
        require __DIR__ . '/includes/views/pages/inicio.php';
        break;

    case 'login':
        require __DIR__ . '/includes/views/pages/login.php';
        break;

    case 'registro':
        require __DIR__ . '/includes/views/pages/registro.php';
        break;

    case 'perfil':
        require __DIR__ . '/includes/views/pages/perfil.php';
        break;

    case 'contacto':
        require __DIR__ . '/includes/views/pages/contacto.php';
        break;

    // --- Productos ---
    case 'categorias':
        require __DIR__ . '/includes/views/pages/categorias.php';
        break;

    case 'productos':
        require __DIR__ . '/includes/views/pages/productos.php';
        break;

    case 'productoDetalle':
        require __DIR__ . '/includes/views/pages/productoDetalle.php';
        break;

    // --- Pedidos ---
    case 'tipoPedido':
        require __DIR__ . '/includes/views/pages/tipoPedido.php';
        break;

    case 'carrito':
        require __DIR__ . '/includes/views/pages/carrito.php';
        break;

    case 'pago':
        require __DIR__ . '/includes/views/pages/pago.php';
        break;

    case 'confirmacionPedido':
        require __DIR__ . '/includes/views/pages/confirmacionPedido.php';
        break;

    case 'estadoPedido':
        require __DIR__ . '/includes/views/pages/estadoPedido.php';
        break;

    // --- Administración ---
    case 'adminUsuarios':
        require __DIR__ . '/includes/views/pages/adminUsuarios.php';
        break;

    case 'adminProductos':
        require __DIR__ . '/includes/views/pages/adminProductos.php';
        break;

    case 'adminPedidos':
        require __DIR__ . '/includes/views/pages/adminPedidos.php';
        break;

    // --- Default ---
    default:
        require __DIR__ . '/includes/views/pages/plantilla.php';
        break;
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
