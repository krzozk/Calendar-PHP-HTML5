<?php
require_once("class/class.consultas.php");
if (isset($_POST['color']) && isset($_POST['curso'])) {
	$color = strip_tags($_POST['color']);
	$id = strip_tags($_POST['curso']);
	$fechas = strip_tags(@$_POST['ffechas']);
	$usuarios = @$_POST['fusuarios'];
	$oRegistroCursoFecha = new CursoFecha;
	$registro = $oRegistroCursoFecha->registrarCursosFecha($id,$color,$fechas,$usuarios);
	if($registro){
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Registro Satisfactorio.</div>";
	}
}
?>