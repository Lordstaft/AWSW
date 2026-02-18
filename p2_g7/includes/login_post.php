<?php
session_start();

// Usuarios de prueba 
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

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';


if (isset($usuarios[$usuario]) && $usuarios[$usuario]['password'] === $password) {

    $_SESSION['user'] = [
        'username' => $usuario,
        'rol' => $usuarios[$usuario]['rol']
    ];

    exit();

} else {

    exit();
}
