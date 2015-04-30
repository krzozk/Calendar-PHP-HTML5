<?php
require_once("class/class.consultas.php");
if (isset($_POST['cursofechasid'])) {
	$fechaid = strip_tags($_POST['cursofechasid']);
	$oRegistroFecha = new Fechas;
	$registro = $oRegistroFecha->eliminarFechaOCursoPorFechaId($fechaid);
	if($registro){
		//echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Registro Satisfactorio.</div>";
	}
}
?>