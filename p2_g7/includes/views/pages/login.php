<?php
$mensaje = '';

if (!empty($_GET['error'])) {
    $mensaje = '<p><strong>Error:</strong> Usuario o contraseña incorrectos.</p>';
}

$contenido = <<<EOS
    <h1>Iniciar sesión</h1>
    $mensaje

    <form method="post" action="includes/login_post.php">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>

    <p>
        ¿No tienes cuenta?
        <a href="index.php?pagina=registro">Regístrate</a>
    </p>
EOS;
