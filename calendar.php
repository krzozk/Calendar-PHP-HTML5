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
//$oDatosPersonas = new Persona;
//$personas_registradas = $oDatosPersonas->obtenerPersonas();
//print_r($personas_registradas);
/* Para registrar Personas */
//$oRegistroPersonas = new Persona;
//$registro = $oRegistroPersonas->registrarPersonas("Irma","Arias",30);
//if($registro){ echo "Registro Satisfactorio"; }



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
			var f=new Date();
			$(document).ready(function(){
				$("#lastmonth").on("click", function(){ 
					$("#operacion").val(-1);
					$('#mes').submit();
				});
				$("#today").on("click", function(){ 
					$("#operacion").val('today');
					$('#mes').submit();
				});
				$("#nextmonth").on("click", function(){ 
					$("#operacion").val(+1);
					$('#mes').submit();
				});
				
				$("#cursofechas").on("click", function(){ 
					$("#operacion").val(+1);
					$('#mes').submit();
				});
				
				$('[data-daydiv="daydiv"]').on("click", function(){ 
					var className = $(this).attr('class');
					alert(className);
					if(className == '' || className == null){
						$( this ).addClass( "dayselected" );
					}else{
						$( this ).removeClass( "dayselected" );
					}
					
				});

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
			});

		</script>
	</head>
	<body>
	<ul class="nav nav-pills">
	  <li class="dropdown">
		<a class="dropdown-toggle"
		   data-toggle="dropdown"
		   href="#">
			Usuario
			<b class="caret"></b>
		  </a>
		<ul class="dropdown-menu">
		  <!-- links -->
		  <a data-toggle="modal" href="#form-content" >Registrar Usuario</a>
		</ul>
	  </li>
	</ul>
	<div class="container">
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
								<div data-daydiv="daydiv">
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
	</body>
</html>