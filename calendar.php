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
//var_dump($cfr);
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Calendario de Cursos</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet" media="screen"> 
		
		<link rel="icon" type="image/png" href="favicon.ico" />
		
		
		<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>

		
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style type="text/css">
			table { margin: auto; }
		</style>
		
		<script>
			var personas = <?php echo json_encode($pr); ?> ;
			var f=new Date();
			var fechas = new Array();
			var numdaysselected = 0;
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
				
				$("#cursofechas").on("click", function(){
					$("#fusuarios").val(personas);
					var p = new Array();
					for(i=0; i < personas.length; i++){
						p.push($("#participantes"+i).data("id")+'-'+$("#participantes"+i).val());
					}
					
					$.ajax({
						type: "POST",
						url: "crearcursofechas.php",
						data: $('form.cursofecha').serialize()+'&fusuarios='+p,
						success: function(msg){
							$("#thanks").html(msg);
							$('form.cursofecha')[0].reset();
							$("#form-content").modal('hide');
						},
						error: function(){
							alert("failure");
						}
					});
					
					//$('#cursofecha').submit();
				});
				
				$('[data-daydiv="daydiv"]').on("click", function(){
					var className = $(this).attr('class');
					if(className == '' || className == null){
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
	<body>
	<ul class="nav nav-pills">
	  <li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">Operaciones<b class="caret"></b></a>
		<ul class="dropdown-menu">
		  <!-- links -->
		  <a data-toggle="modal" href="#form-content" >Registrar Usuario</a>
		  <a data-toggle="modal" href="#form-curso" >Registrar Curso</a>
		</ul>
	  </li>
	</ul>

	<form method="post" id="cursofecha" name="cursofecha" class="cursofecha" >
		<input name="ffechas" id="ffechas" type="hidden" >
		Color: <input name="color" type="color" value="#f3f3f3" />
		<div class="cursos">
			<select name="curso">
				<?php 
					foreach($cursos_registrados as $clave => $valor){
						echo '<option value='.$valor['id'].' data-id='.$valor['id'].'>'.
							$valor['nombre']
						.'</option>';
					}
				?>
			</select> 
		</div>
		<div class="usuarios">
			<ul>
			<?php 
				$i= 0;
				foreach($pr as $clave => $valor){
					echo '<li data-id='.$valor['id'].'>'.
						$valor['nombre'].' '.$valor['apellido_paterno'].' '.$valor['apellido_materno']
					.'</li>';
					echo '<input type="number" id="participantes'.$i.'" min="0" max="50" value="0" data-id='.$valor['id'].'>';
					$i++;
				}
			?>
			</ul>
			
		</div>
	</form>
		<div id="thanks"></div>
		<table border="1">
			<thead>
				<tr>
					<td colspan="7">
						<?php echo ucwords( strftime( '%B %Y', strtotime( $month ) ) ); ?>
						<form method="post" id="mes" style="display:inline;">
							<input type="hidden" name="month" id="month" value="<?php echo $month; ?>">
							<input type="hidden" name="operacion" id="operacion" >
							<input type="button" id="lastmonth" value="<">
							<input type="button" id="today" value="-">
							<input type="button" id="nextmonth" value=">">
						</form>
						<button class="btn btn-primary" id="cursofechas" >Guardar</button>
					</td>
				</tr>
				<tr>
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
							<td>
								<?php echo isset( $days[ $i ] ) ? $days[ $i ] : ''; ?>
								<div data-daydiv="daydiv" data-fecha="<?php echo strftime( '%Y-%m-', strtotime( $month ) ).(isset( $days[ $i ] ) ? (($days[ $i ]>0&&$days[ $i ]<10)?('0'.$days[ $i ]):$days[ $i ]) : ''); ?>">
								</div>
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
	
	</body>
</html>