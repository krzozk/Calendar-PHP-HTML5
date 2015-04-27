<?php
require_once("class/class.consultas.php");
if (isset($_POST['cursopersonaid'])) {
	$cursopersonaid = strip_tags($_POST['cursopersonaid']);
	$participantes = strip_tags($_POST['participantes']);
	$oRegistroCursoPersona = new CursoPersona;
	$registro = $oRegistroCursoPersona->actualizarParticipantes($cursopersonaid,$participantes);
	if($registro){
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Actualización Satisfactoria.</div>";
	}
}
?>