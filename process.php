<?php
require_once("class/class.consultas.php");
if (isset($_POST['nombre'])) {
	$nombre = strip_tags($_POST['nombre']);
	$apellido_paterno = strip_tags($_POST['apellido_paterno']);
	$apellido_materno = strip_tags($_POST['apellido_materno']);
	$oRegistroPersonas = new Persona;
	$registro = $oRegistroPersonas->registrarPersonas($nombre,$apellido_paterno,$apellido_materno);
	if($registro){
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Registro Satisfactorio.</div>";
	}
}
?>