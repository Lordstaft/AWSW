<?php
$mensaje = '';

if (!empty($_GET['error'])) {
    $mensaje = '<p><strong>Error:</strong> Revisa los datos del formulario.</p>';
}

if (!empty($_GET['ok'])) {
    $mensaje = '<p><strong>Registro completado.</strong> Ahora puedes iniciar sesión.</p>';
}

$contenido = <<<EOS
    <h1>Registro</h1>
    $mensaje

    <form method="post" action="includes/registro_post.php">
        <label for="usuario">Nombre de usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Crear cuenta</button>
    </form>

    <p>
        ¿Ya tienes cuenta?
        <a href="index.php?pagina=login">Inicia sesión</a>
    </p>
EOS;
