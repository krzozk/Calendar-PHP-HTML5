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
//var_dump($cursosfecha_registrados);
/*
foreach($cursosfecha_registrados as $clave => $valor){
	if($valor!=0){
		$oDatosFechas = new Fechas;
		$fechas_registradas = $oDatosFechas->obtenerFechasPorIdCursoFecha($cursosfecha_registrados[$clave]['id']);
		$fraux = array();
		foreach($fechas_registradas as $frc => $frv){
			if($frv!=0){
				array_push($fraux,$fechas_registradas[$frc]);
			}
		}
		$cursosfecha_registrados[$clave]['fechas'] = $fraux;
		//var_dump($fechas_registradas);
		$oDatosCursoPersonas = new CursoPersona;
		$personas_registradas = $oDatosCursoPersonas->obtenerPersonasPorIdCursoFecha($cursosfecha_registrados[$clave]['id']);
		$praux = array();
		foreach($personas_registradas as $prc => $prv){
			if($prv!=0){
				array_push($praux,$personas_registradas[$prc]);
			}
		}
		//var_dump($personas_registradas);
		$cursosfecha_registrados[$clave]['personas'] = $praux;
		array_push($cfr,$cursosfecha_registrados[$clave]);
	}
}
*/
//var_dump($cfr);
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
				
				$(".cerrar").on("click", function(){
					var fid = $(this).data('fid');
					$.ajax({
						type: "POST",
						url: "eliminarfechaycurso.php",
						data: {cursofechasid:fid},
						success: function(msg){
							$('.cursoregistrado[data-fid="'+fid+'"]').remove();
							$("#thanks").html(msg);
						},
						error: function(){
							alert("failure");
						}
					});
				});
				
				$(".agregar").on("click", function(){
					var cfid = $(this).data('cursofechasid');
					$.ajax({
						type: "POST",
						url: "agregarfechasacurso.php",
						data: {cursofechasid:cfid,fechas:$("#ffechas").val()},
						success: function(msg){
							$("#thanks").html(msg);
							window.location.reload(true);
						},
						error: function(){
							alert("failure");
						}
					});
				});
				
				$("#cursofechas").on("click", function(){
					$("#fusuarios").val(personas);
					var p = new Array();
					for(i=0; i < personas.length; i++){
						p.push($("#participantes"+i).data("id")+'-'+$("#participantes"+i).val());
					}
					console.log($('form.cursofecha').serialize()+'&fusuarios='+p);
					$.ajax({
						type: "POST",
						url: "crearcursofechas.php",
						data: $('form.cursofecha').serialize()+'&fusuarios='+p,
						success: function(msg){
							$("#thanks").html(msg);
							$('form.cursofecha')[0].reset();
							window.location.reload(true);
							//$("#form-content").modal('hide');
						},
						error: function(){
							alert("failure");
						}
					});
					//$('#cursofecha').submit();
				});
				
				$('[class="alta"]').on("click", function(){
					console.log('alta');
					var className = $(this).attr('class');
					if(className == 'alta'){
						$( this ).addClass( "dayselected" );
						var msg = "";
						msg = "<div data-id>"+ 
							""
							+"</div>";
						$("#thanks").html(msg);
						fechas.push( $(this).data("fecha") );
						fechas.sort(function(a,b){
										var c = new Date(a);
										var d = new Date(b);
										return c-d;
									});
						$("#ffechas").val(fechas);
					}else{
						$( this ).removeClass( "dayselected" );
						fechas.splice( fechas.indexOf( $(this).data("fecha") ), 1 );
						$("#ffechas").val(fechas);
					}
				});
				
				/*
				$('.usuarios ul li').on("click", function(){ 
					console.log('click');
					var className = $(this).attr('class');
					if(className == '' || className == null){
						$( this ).addClass( "check" );
					}else{
						$( this ).removeClass( "check" );
					}
					
				});*/

			});
			
			$(function() {
				//twitter bootstrap script
				$("button#submitusuario").click(function(){
					$.ajax({
						type: "POST",
						url: "process.php",
						data: $('form.usuario').serialize(),
						success: function(msg){
							$("#thanks").html(msg);
							$('form.usuario')[0].reset();
							$("#form-content").modal('hide');
						},
						error: function(){
							alert("failure");
						}
					});
				});
				$("button#submitcurso").click(function(){
					$.ajax({
						type: "POST",
						url: "crearcurso.php",
						data: $('form.curso').serialize(),
						success: function(msg){
							$("#thanks").html(msg);
							$('form.curso')[0].reset();
							$("#form-curso").modal('hide');
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
					<ul class="nav nav-pills">
					  <li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Operaciones<b class="caret"></b></a>
						<ul class="dropdown-menu">
						  <!-- links -->
						  <li><a data-toggle="modal" href="#form-content" >Registrar Usuario</a></li>
						  <li><a data-toggle="modal" href="#form-curso" >Registrar Curso</a></li>
						</ul>
					  </li>
					</ul>
					<form method="post" id="cursofecha" name="cursofecha" class="cursofecha form-horizontal"  role="form" >
						<input name="ffechas" id="ffechas" type="hidden" >
						<div class="form-group espacio">
							<label class="col-sm-1" for="color">Color:</label>
							<div class="col-sm-2">
								<input name="color" type="color" value="#f3f3f3" />
							</div>
						</div>
						<div class="form-group espacio">
							<label class="col-sm-1" for="color">Curso:</label>
							<div class="col-sm-2">
								<select name="curso">
									<?php 
										foreach($cr as $clave => $valor){
											echo '<option value='.$valor['id'].' data-id='.$valor['id'].'>'.
												$valor['nombre']
											.'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="usuarios espacio">
								<ul>
								<?php 
									$i= 0;
									foreach($pr as $clave => $valor){
										echo '<li data-id='.$valor['id'].'>'.
											'<p><input type="number" id="participantes'.$i.'" min="0" max="50" value="0" data-id='.$valor['id'].' style=" width:35px; height:10px; pattern="[0-9]{2}" onkeypress="return justNumbers(event);" >'.
											' '.$valor['nombre'].' '.$valor['apellido_paterno'].' '.$valor['apellido_materno'].'</p>'
										.'</li>';
										$i++;
									}
								?>
								</ul>
						</div>
					</form>
					<button class="btn btn-primary" id="cursofechas" >Guardar</button>
					<br><br>
					<a href="index.php" > Ir a Calendario de Usuarios </a>
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
										<?php $fechadia = strftime( '%Y-%m-', strtotime( $month ) ).(isset( $days[ $i ] ) ? (($days[ $i ]>0&&$days[ $i ]<10)?('0'.$days[ $i ]):$days[ $i ]) : ''); ?>
										<td>
											<div <?php echo isset( $days[ $i ] ) ? 'class="alta"' : ''; ?> data-fecha="<?php echo $fechadia; ?>" >
												<?php echo isset( $days[ $i ] ) ? $days[ $i ] : ''; ?>
											</div>
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
													echo '
													<div data-fid="'.$fv['fid'].'" data-fecha="'.$fechadia.'" data-cursofechasid="'.$fv['curso_fechas_id'].'" data-cursoid="'.$fv['curso_id'].'" data-nombre="'.$fv['nombre'].'" data-color="'.$fv['color'].'" style="background-color:'.$fv['color'].'; font-size:0.8em; " class="cursoregistrado" >
														<div class="curso" data-fid="'.$fv['fid'].'">
															<div class="nombrecurso" data-cursofechasid="'.$fv['curso_fechas_id'].'" >
																<strong>'.($participantes[0]['participantes']).'</strong> - '.
																$fv['nombre'].'
															</div>
															<div class="divagregar"><button type="button" class="agregar" data-fid="'.$fv['fid'].'" data-fecha="'.$fechadia.'" data-cursofechasid="'.$fv['curso_fechas_id'].'" data-cursoid="'.$fv['curso_id'].'" data-nombre="'.$fv['nombre'].'" data-color="'.$fv['color'].'" >+</button></div>
															<div class="divcerrar"><button type="button" class="cerrar" data-fid="'.$fv['fid'].'" data-fecha="'.$fechadia.'" data-cursofechasid="'.$fv['curso_fechas_id'].'" data-cursoid="'.$fv['curso_id'].'" data-nombre="'.$fv['nombre'].'" data-color="'.$fv['color'].'" >&times;</button></div>
														</div>
													</div>
													';
												}
											?>
											
										</td>
									<?php endfor; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<div class="container">
						<!-- model content -->	
						<div id="form-content" class="modal hide fade in" style="display: none; ">
							<div class="modal-header">
								<a class="close" data-dismiss="modal">×</a>
								<h3>Registrar Usuario</h3>
							</div>
							<div>
								<form class="usuario" >
									<fieldset>
										<div class="modal-body">
											<ul class="nav nav-list">
												<li class="nav-header">Nombre</li>
												<li><input class="input-xlarge" value="" type="text" name="nombre" value=""></li>
												<li class="nav-header">Apellido Paterno</li>
												<li><input class="input-xlarge" value="" type="text" name="apellido_paterno" value=""></li>
												<li class="nav-header">Apellido Materno</li>
												<li><input class="input-xlarge" value="" type="text" name="apellido_materno" value=""></li>
											</ul> 
										</div>
									</fieldset>
								</form>
							</div>
							<div class="modal-footer">
								<button class="btn btn-success" id="submitusuario">Guardar</button>
								<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
							</div>
						</div>
					</div>
					
					<div class="container">
						<!-- model content -->	
						<div id="form-curso" class="modal hide fade in" style="display: none; ">
							<div class="modal-header">
								<a class="close" data-dismiss="modal">×</a>
								<h3>Registrar Curso</h3>
							</div>
							<div>
								<form class="curso" >
									<fieldset>
										<div class="modal-body">
											<ul class="nav nav-list">
												<li class="nav-header">Nombre</li>
												<li><input class="input-xlarge" value="" type="text" name="nombre" value=""></li>
											</ul> 
										</div>
									</fieldset>
								</form>
							</div>
							<div class="modal-footer">
								<button class="btn btn-success" id="submitcurso">Guardar</button>
								<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>