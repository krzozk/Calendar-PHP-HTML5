<?php
require_once("class/class.consultas.php");
if (isset($_POST['cursofechasid'])) {
	$cursofechasid = strip_tags($_POST['cursofechasid']);
	$color = $_POST['color'];
	$oCursoFecha = new CursoFecha;
	$registro = $oCursoFecha->cambiarColorPorCursoFechaId($cursofechasid,$color);
	if($registro){
		//echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Cambio el color.</div>";
	}
}
?>