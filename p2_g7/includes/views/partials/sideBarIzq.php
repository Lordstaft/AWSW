<?php
function mostrarAdministrar() {
	if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']===true) ) {
		return "<li><a href='/../pages/admin.php'>Administrar</a></li>";
		
	}
}
function mostrarNavegacion() {
    if (isset($_SESSION['login']) && $_SESSION['login']===true) ) {
        return "<li><a href='/ej2_p12/01-inicio/01-inicio/01-inicio/includes/views/plantillas/admin.php'>Administrar</a></li>";
?>

<nav id="sidebarIzq">
	<h3>Navegación</h3>
	<ul>
        <?= mostrarAdministrar(); ?>
        <?= mostrarNavegacion(); ?>
	</ul>
</nav>
