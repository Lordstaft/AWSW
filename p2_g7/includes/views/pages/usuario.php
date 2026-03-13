<?php
require_once __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\Usuario;

$tituloPagina = 'Perfil';
$busqueda = $_SESSION['nombreUsuario'];

$usuario = Usuario::buscaUsuario($busqueda);

$contenidoPrincipal = <<<EOS
    <h1>Perfil</h1>
    <div>
        <img src="{$usuario->getAvatar()}" alt="Avatar de {$usuario->getAvatar()}">

        <p><strong>Nombre de usuario:</strong> {$usuario->getNombreUsuario()}</p>
        <p><strong>Nombre:</strong> {$usuario->getNombre()}</p>
        <p><strong>Apellidos:</strong> {$usuario->getApellidos()}</p>
        <p><strong>Email:</strong> {$usuario->getEmail()}</p>
        <p><strong>Rol:</strong> {$usuario->getRol()}</p>
        <p><strong>Fecha de registro:</strong> {$usuario->getFechaRegistro()}</p>
    </div>
EOS;

require __DIR__ . '/plantilla.php';