<?php
require_once("class/class.consultas.php");
if (isset($_POST['id'])) {
	$id = strip_tags($_POST['id']);
	$nombre = strip_tags($_POST['nombre']);
	$oPersona = new Persona;
	$registro = $oPersona->editar($id,$nombre);
	if($registro){
		//echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Ha cambiado el nombre del usuario.</div>";
	}
}
?>