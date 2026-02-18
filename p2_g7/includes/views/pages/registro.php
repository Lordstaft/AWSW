<?php
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario   = trim($_POST['usuario'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $password  = $_POST['password'] ?? '';

    if ($usuario === '' || $email === '' || $nombre === '' || $apellidos === '' || $password === '') {
        $mensaje = '<p><strong>Error:</strong> Todos los campos son obligatorios.</p>';
    } else {

        $_SESSION['registered_users'][$usuario] = [
            'email' => $email,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'password' => $password,
            'rol' => 'cliente'
        ];

        $mensaje = '<p><strong>Registro completado correctamente.</strong></p>';
    }
}

$contenido = <<<EOS
    <h1>Registro</h1>
    $mensaje

    <form method="post" action="index.php?pagina=registro">
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
