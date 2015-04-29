<?php
// Establecer el idioma al Español para strftime().
setlocale( LC_TIME, 'spanish' );
// Si no se ha seleccionado mes, ponemos el actual y el año
$month = isset( $_POST[ 'month' ] ) ? $_POST[ 'month' ] : date( 'Y-n' );
$operacion = isset( $_POST[ 'operacion' ] ) ? $_POST[ 'operacion' ] : 0;
$week = 1;
$a = substr($month,0,4);
$m = substr($month,5);
$resultado = $m + $operacion;
$m = $resultado;
if($resultado>12){
	$a = $a + 1;
	$m = 1;
}elseif($resultado<1){
	$a = $a - 1;
	$m = 12;
}
$month = $a.'-'.$m;
if($operacion=='today'){
	$month = date( 'Y-n' );
}
for ( $i=1;$i<=date( 't', strtotime( $month ) );$i++ ) {
	$day_week = date( 'N', strtotime( $month.'-'.$i )  );
	$calendar[ $week ][ $day_week ] = $i;
	if ( $day_week == 7 )
		$week++;
}

//requerimos solo la clase consultas
require_once("class/class.consultas.php");
/* Para consultar Personas */
$oDatosPersonas = new Persona;
$personas_registradas = $oDatosPersonas->obtenerPersonas();
$pr = array();
foreach($personas_registradas as $clave => $valor){
	if($valor!=0){
		array_push($pr,$personas_registradas[$clave]);
	}
}
/* Para consultar Cursos */
$oDatosCursos = new Curso;
$cursos_registrados = $oDatosCursos->obtenerCursos();
$cr = array();
foreach($cursos_registrados as $clave => $valor){
	if($valor!=0){
		array_push($cr,$cursos_registrados[$clave]);
	}
}
/* Para consultar Curso agendado y sus participantes */
$oDatosCursosFecha = new CursoFecha;
$cursosfecha_registrados = $oDatosCursosFecha->obtenerCursosFecha();
$cfr = array();

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Calendario de Cursos</title>
		
		<script src="jquery.js"></script>
		<link href="bootstrap-combined.min.css" rel="stylesheet" media="screen"> 
		
		<link rel="icon" type="image/png" href="favicon.ico" />
		
		
		<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
		<!--[if lt IE 9]>
		  <script src="html5shiv.js"></script>
		  <script src="respond.min.js"></script>
		<![endif]-->
		<script src="bootstrap.min.js"></script>

		
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style type="text/css">
			table { margin: auto; }
		</style>
		
		<script>
			var personas = <?php echo json_encode($pr); ?> ;
			var f=new Date();
			var fechas = new Array();
			var numdaysselected = 0;
			
			function justNumbers(e)
			{
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
					return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			
			$(document).ready(function(){
				$("#lastmonth").on("click", function(){ 
					$("#operacion").val(-1);
					numdaysselected--;
					$('#mes').submit();
				});
				$("#today").on("click", function(){ 
					$("#operacion").val('today');
					$('#mes').submit();
				});
				$("#nextmonth").on("click", function(){ 
					$("#operacion").val(+1);
					numdaysselected++;
					$('#mes').submit();
				});
				
				$(".cursoregistrado").on("click", function(){
					var msg = '<form method="post" id="participantes" name="participantes" class="cursofecha form-horizontal" role="form">';
					msg += '<p><input type="text" style="background-color:'+$(this).data("color")+'; width:20px; height:10px; " name="colorcourse" readonly disabled >'
					msg += '  '+$(this).data("nombre")+'</p>';
					msg += '<input type="hidden" value="'+$(this).data("cursofechasid")+'" name="cursofechasid">'
					msg += '<input type="hidden" value="'+$(this).data("color")+'" name="color">'
					msg += '<input type="hidden" value="'+$(this).data("cursoid")+'" name="cursoid">'
					msg += '<div id="usuarios" class="usuarios espacio"></div>';
					//msg += '<button class="btn btn-primary" id="cursofechas" >Actualizar</button>'
					msg += '</form>';
					$("#actualizaparticipantes").html(msg);
					
					$.ajax({
						type: "POST",
						url: "obtenerparticipantes.php",
						data: {cursofechasid:$(this).data("cursofechasid")},
						success: function(response){
							//alert(response);
							$("#usuarios").html(response);
							msg += response;
							//$('form.participantes')[0].reset();
						},
						error: function(){
							alert("failure");
						}
					});
					
				});
				
			});
		</script>
	</head>
	<body BGCOLOR="#f3f3f3">
		<div class="container-fluid">
			<div class="row-fluid espacioarriba">
				<div class="span3">
					<div id="actualizaparticipantes" style="display:block;"></div>
				</div>
				<div class="span9">
					<div id="thanks"></div>
					<table border="1" style="width: 601px; height: 415px;" >
						<thead>
							<tr style="height:26px;">
								<td colspan="7">
									<?php echo ucwords( strftime( '%B %Y', strtotime( $month ) ) ); ?>
									<form method="post" id="mes" style="display:inline;">
										<input type="hidden" name="month" id="month" value="<?php echo $month; ?>">
										<input type="hidden" name="operacion" id="operacion" >
										<input type="button" id="lastmonth" value="<">
										<input type="button" id="today" value="-">
										<input type="button" id="nextmonth" value=">">
									</form>
								</td>
							</tr>
							<tr style="height:26px;">
								<td>Lunes</td>
								<td>Martes</td>			
								<td>Miércoles</td>			
								<td>Jueves</td>			
								<td>Viernes</td>			
								<td>Sábado</td>			
								<td>Domingo</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $calendar as $days ) : ?>
								<tr>
									<?php for ( $i=1;$i<=7;$i++ ) : ?>
										<td valign="top">
											<?php echo isset( $days[ $i ] ) ? $days[ $i ] : ''; ?>
											<?php $fechadia = strftime( '%Y-%m-', strtotime( $month ) ).(isset( $days[ $i ] ) ? (($days[ $i ]>0&&$days[ $i ]<10)?('0'.$days[ $i ]):$days[ $i ]) : ''); ?>
											<?php
												$oDatosFechas = new Fechas;
												$fechas_registradas = $oDatosFechas->obtenerFechasPorFecha($fechadia);
												$fraux = array();
												foreach($fechas_registradas as $frc => $frv){
													if($frv!=0){
														array_push($fraux,$fechas_registradas[$frc]);
													}
												}
												foreach($fraux as $fc => $fv){
													$oCursoPersona = new CursoPersona;
													$participantes = $oCursoPersona->obtenerParticipantesPorCursoFechaId($fv['curso_fechas_id']);
													echo '<div data-fid="'.$fv['fid'].'" data-fecha="'.$fechadia.'" data-cursofechasid="'.$fv['curso_fechas_id'].'" data-cursoid="'.$fv['curso_id'].'" data-nombre="'.$fv['nombre'].'" data-color="'.$fv['color'].'" style="background-color:'.$fv['color'].'; font-size:0.8em; " class="cursoregistrado" >
													<strong data-cursofechasid="'.$fv['curso_fechas_id'].'" class="numeroparticipantes" >'.($participantes[0]['participantes']).'</strong> - '.$fv['nombre'].'
													</div>';
												}
											?>
										</td>
									<?php endfor; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>