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
			$personas_registradas[$prc]['personaapellidopaterno'] = $pr[0]['apellido_paterno'];
			$personas_registradas[$prc]['personaapellidomaterno'] = $pr[0]['apellido_materno'];
			array_push($praux,$personas_registradas[$prc]);
		}
	}
	if($praux){
		echo '<ul>';
		foreach($praux as $c => $v){
			echo '<li><p><input class="target" pattern="[0-9]{2}" type="number" min="0" max="50" value="'.$v['participantes'].'" data-id="'.$v['id'].'" style=" width:35px; height:10px; " onkeypress="return justNumbers(event);" /> '.$v['personanombre'].' '.$v['personaapellidopaterno'].' '.$v['personaapellidomaterno'].'</p></li>';
		}
		echo '</ul>';
		echo '<script>
				
				$( ".target" ).change(function() {
					//if(is_integer($(this).val())){
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
					//}
				});
			  </script>
				';
	}
}
?>