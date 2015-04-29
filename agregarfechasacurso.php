<?php
require_once("class/class.consultas.php");
if (isset($_POST['cursofechasid'])&&!empty($_POST['fechas'])) {
	$fechaid = strip_tags($_POST['cursofechasid']);
	$fechas = $_POST['fechas'];
	$f = explode(",",$fechas);
	$registro = false;
	foreach($f as $clave => $valor){
		$oRegistroFecha = new Fechas();
		$registro = $oRegistroFecha->agregarFechaACursoPorCursoFechaId($fechaid,$valor);
	}	
	if($registro){
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Registro Satisfactorio.</div>";
	}
}
?>