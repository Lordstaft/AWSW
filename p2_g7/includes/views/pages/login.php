<?php
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuarios = [
        'cliente1' => [
            'password' => '1234',
            'rol' => 'cliente'
        ],
        'gerente1' => [
            'password' => 'admin',
            'rol' => 'gerente'
        ]
    ];


    if (isset($usuarios[$usuario]) && $usuarios[$usuario]['password'] === $password) {

        $_SESSION['user'] = [
            'username' => $usuario,
            'rol' => $usuarios[$usuario]['rol']
        ];

        $mensaje = '<p><strong>Sesión iniciada correctamente.</strong></p>';

    } else {
        $mensaje = '<p><strong>Error:</strong> Usuario o contraseña incorrectos.</p>';
    }
}

$contenido = <<<EOS
    <h1>Iniciar sesión</h1>
    $mensaje

    <form method="post" action="index.php?pagina=login">
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
