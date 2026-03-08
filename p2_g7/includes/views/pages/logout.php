<?php
require __DIR__ . '/../../config.php';
$_SESSION = [];

session_destroy();

header("Location: " . RUTA_APP . "/index.php");
exit;
