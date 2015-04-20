<?php
// Establecer el idioma al Español para strftime().
setlocale( LC_TIME, 'spanish' );
// Si no se ha seleccionado mes, ponemos el actual y el año
$month = isset( $_GET[ 'month' ] ) ? $_GET[ 'month' ] : date( 'Y-n' );
$week = 1;
for ( $i=1;$i<=date( 't', strtotime( $month ) );$i++ ) {
	$day_week = date( 'N', strtotime( $month.'-'.$i )  );
	$calendar[ $week ][ $day_week ] = $i;
	if ( $day_week == 7 )
		$week++;
}
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<title>Calendario de Cursos</title>
		<link rel="stylesheet" type="text/css" href="bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style type="text/css">
			table { margin: auto; }
		</style>
		
		<script 
		<script type="text/javascript"  src="jquery.js" ></script>
		
		<script>
			var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			var f=new Date();
			$(document).ready(function(){
				$("#lastmonth").on("click", function(){ 
					//lastMonth();
					if(f.getMonth()==01){
						if(f.getDay()>=28){
							f.setDay(28);
						}
					}
					f.setMonth(f.getMonth()-1);
					console.log(f.getFullYear()+'-'+(f.getMonth()));
					$('#month').val(f.getFullYear()+'-'+f.getMonth());
					alert(f);
				});
				$("#nextmonth").on("click", function(){ 
					//nextMonth();
					if(f.getMonth()==01){
						if(f.getDay()>=28){
							f.setDay(28);
						}
					}
					f.setMonth(f.getMonth()+1);
					console.log(f.getFullYear()+'-'+(f.getMonth()));
					$('#month').val(f.getFullYear()+'-'+f.getMonth());
					alert(f);
				});
			});
		</script>
	</head>
	<body>
		<table border="1">
			<thead>
				<tr>
					<td colspan="7"><?php echo ucwords( strftime( '%B %Y', strtotime( $month ) ) ); ?></td>
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
							</td>
						<?php endfor; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						<form method="get">
							<input type="month" name="month" id="month" >
							<input type="button" id="lastmonth">
							<input type="button" id="nextmonth">
						</form>
					</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>