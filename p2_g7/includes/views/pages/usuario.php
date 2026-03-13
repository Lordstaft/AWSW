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

use es\ucm\fdi\aw\Aplicacion;

$conn = Aplicacion::getInstance()->getConexionBd();

$idUsuario = $usuario->getId();

$query = "
SELECT numPedido, estadoPedido, total, fechaPedido
FROM pedidos
WHERE usuario_id = $idUsuario
ORDER BY fechaPedido DESC
";

$res = $conn->query($query);

$contenidoPrincipal .= "<h2>Mis pedidos</h2>";

if ($res->num_rows == 0) {

    $contenidoPrincipal .= "<p>No tienes pedidos realizados.</p>";

} else {

    $contenidoPrincipal .= "<ul>";

    while ($fila = $res->fetch_assoc()) {

        $contenidoPrincipal .= "
        <li>
        Pedido {$fila['numPedido']} -
        Estado: {$fila['estadoPedido']} -
        Total: {$fila['total']} € -
        {$fila['fechaPedido']}
        </li>
        ";
    }

    $contenidoPrincipal .= "</ul>";
}

require __DIR__ . '/plantilla.php';