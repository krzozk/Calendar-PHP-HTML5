<?php
require_once("class/class.consultas.php");
if (isset($_POST['cursofechasid'])) {
	$cursoFechaId = strip_tags($_POST['cursofechasid']);
	$oDatosCursoPersonas = new CursoPersona;
	$personas_registradas = $oDatosCursoPersonas->obtenerPersonasPorIdCursoFecha($cursoFechaId);
	$praux = array();
	foreach($personas_registradas as $prc => $prv){
		if($prv!=0){
			$oDatosPersonas = new Persona;
			$persona = $oDatosPersonas->obtenerPersonasPorId($prv['persona_id']);
			$pr = array();
			foreach($persona as $clave => $valor){
				if($valor!=0){
					array_push($pr,$persona[$clave]);
				}
			}
			$personas_registradas[$prc]['personanombre'] = $pr[0]['nombre'];
			array_push($praux,$personas_registradas[$prc]);
		}
	}
	if($praux){
		$participantesTotal = 0;
		echo 'Participantes';
		echo '<ul>';
		foreach($praux as $c => $v){
			echo '<li><p><input class="target" pattern="[0-9]{2}" type="number" min="0" max="50" value="'.$v['participantes'].'" data-id="'.$v['id'].'" data-cursofechasid="'.$v['curso_fechas_id'].'" style=" width:45px; height:15px; " onkeypress="return justNumbers(event);" /> '.$v['personanombre'].' </p></li>';
			$participantesTotal += $v['participantes'];
		}
		echo '</ul>';
		if($participantesTotal==1){
			echo '<strong class="numeroparticipantes" data-cursofechasid="'.$v['curso_fechas_id'].'" >'.$participantesTotal. '</strong> participante';
		}else{
			echo '<strong class="numeroparticipantes" data-cursofechasid="'.$v['curso_fechas_id'].'" >'.$participantesTotal. '</strong> participantes';
		}
		echo '<script>
				$( ".target" ).change(function() {
					var current= $(this).data("cursofechasid");
					var total = 0;
					$(".target").each(function(){
						total=total+parseInt($(this).val());
					})
					$(".numeroparticipantes[data-cursofechasid=\'"+current+"\']").html(total);
					
					$.ajax({
						type: "POST",
						url: "actualizarparticipantes.php",
						data: {cursopersonaid: $(this).data("id"),participantes:$(this).val()},
						success: function(msg){
							$("#thanks").html(msg);
						},
						error: function(){
							alert("failure");
						}
					});
					
				});
			  </script>
				';
	}
}
?>