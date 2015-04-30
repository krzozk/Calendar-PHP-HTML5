<?php
require_once("class/class.consultas.php");
if (isset($_POST['id'])) {
	$id = strip_tags($_POST['id']);
	$oPersona = new Persona;
	$registro = $oPersona->eliminarPersonaPorId($id);
	if($registro){
		//echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Usuario Eliminado.</div>";
	}
}
?>