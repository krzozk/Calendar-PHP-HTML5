<?php
require_once("class/class.consultas.php");
if (isset($_POST['nombre'])) {
	$nombre = strip_tags($_POST['nombre']);
	$oRegistroPersonas = new Persona;
	$registro = $oRegistroPersonas->registrarPersonas($nombre);
	if($registro){
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Registro Satisfactorio.</div>";
	}
}
?>