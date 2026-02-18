<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bistro FDI</title>
</head>
<body>

    <?php include './includes/views/partials/cabecera.php'; ?>

    <?php include './includes/views/partials/sideBarIzq.php'; ?>

    <?php
        $pagina = $_GET['pagina'] ?? 'inicio';
        
        switch ($pagina) {
            case 'contacto':
                require './includes/views/pages/contacto.php';
                break;
            case 'takeAway':
                require './includes/views/pages/takeAway.php';
                break;
            case 'eatIn':
                require './includes/views/pages/eatIn.php';
                break;
            default:
                require './includes/views/pages/plantilla.php';
        }
    ?>
    
    <main>
        <article>
            <?php echo $contenido; ?>
        </article>
    </main>

    <?php include './includes/views/partials/sideBarDer.php'; ?>
    <?php include './includes/views/partials/pie.php'; ?>

</body>
</html>
