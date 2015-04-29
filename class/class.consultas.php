<?php
/**
 * @author globallynx.com
 * @copyright 2015
 *
 * #############################
 * Archivo de clases principales
 */
 
//Se requiere el archivo de configuracion
require("cfg/config.php");

class conectorDB extends configuracion //clase principal de conexion y consultas
{
	private $conexion;
	public function __construct(){
		$this->conexion = parent::conectar(); //creo una variable con la conexión
		return $this->conexion;										
	}
	public function consultarBD($consulta, $valores = array()){  //funcion principal, ejecuta todas las consultas
		$resultado = false;
		if($statement = $this->conexion->prepare($consulta)){  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //inserto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[substr($parametro,1)]);
				}
			}
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				}
				
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				array_push($resultado,$this->conexion->lastInsertId());
				
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->conexion = null; //cerramos la conexión
	} /// Termina funcion consultarBD
}/// Termina clase conectorDB

class Persona
{
	private $personas;
	public function obtenerPersonas(){
		$consulta = "SELECT * FROM personas";
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->personas = $oConectar->consultarBD($consulta,$valores);
		return $this->personas;
	} //Termina funcion obtenerPersonas();
	public function obtenerPersonasPorId($persona){
		$consulta = "SELECT * FROM personas WHERE id = ".$persona;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->personas = $oConectar->consultarBD($consulta,$valores);
		return $this->personas;
	} //Termina funcion obtenerPersonasPorId();
	public function registrarPersonas($nombre,$apellidoPaterno,$apellidoMaterno){
        $registrar = false; //creamos una variable de control
		$consulta = "INSERT INTO personas(nombre,apellido_paterno,apellido_materno)
					VALUES (:nombre, :apellido_paterno, :apellido_materno)";
		//VALORES PARA REGISTRO
		$valores = array("nombre"=>$nombre,
						"apellido_paterno"=>$apellidoPaterno,
						"apellido_materno"=>$apellidoMaterno);
		$oConexion = new conectorDB; //instanciamos conector
		$registrar = $oConexion->consultarBD($consulta, $valores);
		if($registrar !== false){
			return true;
		}
		else{
			return false;
		}
    } //Termina funcion registrarUsuarios()
}/// TERMINA CLASE USUARIOS ///

class Curso
{
	private $cursos;
	public function obtenerCursos(){
		$consulta = "SELECT * FROM cursos";
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursos = $oConectar->consultarBD($consulta,$valores);
		return $this->cursos;
	} //Termina funcion obtenerCursos();
	public function registrarCurso($nombre){
        $registrar = false; //creamos una variable de control
		$consulta = "INSERT INTO cursos(nombre,creado)
					VALUES (:nombre, :creado)";
		//VALORES PARA REGISTRO
		$valores = array("nombre"=>$nombre,
						"creado"=>date('Y-m-d'));
		$oConexion = new conectorDB; //instanciamos conector
		$registrar = $oConexion->consultarBD($consulta, $valores);
		if($registrar !== false){
			return true;
		}
		else{
			return false;
		}
    } //Termina funcion registrarCursos()
	public function obtenerCursoPorId($id){
		$consulta = "SELECT * FROM cursos WHERE id = ".$id;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursos = $oConectar->consultarBD($consulta,$valores);
		return $this->cursos;
	} //Termina funcion obtenerCursos();
}/// TERMINA CLASE CURSOS ///

class CursoFecha
{
	private $cursosFecha;
	public function obtenerCursosFecha(){
		$consulta = "SELECT * FROM curso_fechas";
		$valores = null;		
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursosFecha = $oConectar->consultarBD($consulta,$valores);
		return $this->cursosFecha;
	} //Termina funcion obtenerCursosFechas();
	public function registrarCursosFecha($curso_id,$color,$fechas,$usuarios){
        $registrar = false; //creamos una variable de control
		$c = new Curso;
		$cursox = $c->obtenerCursoPorId($curso_id);
		$consulta = "INSERT INTO curso_fechas(curso_id,nombre,color)
					VALUES (:curso_id, :nombre, :color)";
		//VALORES PARA REGISTRO
		$valores = array("curso_id"=>$curso_id,
						"nombre"=>$cursox[0]['nombre'],
						"color"=>$color);
		$oConexion = new conectorDB; //instanciamos conector
		$registrar = $oConexion->consultarBD($consulta, $valores);
		//Registrar los dias seleccionados para el curso
		if($registrar[0]>0){
			$f = explode(",",$fechas);
			foreach($f as $clave => $valor){
				$oF = new Fechas();
				$oF->registarFechaCurso($registrar[0],$valor,$oConexion);
			}
			//Registrar los usuarios y sus participantes
			$u = explode(",",$usuarios);
			foreach($u as $clave => $valor){
				$us = explode("-",$u[$clave]);
				$oCursoPersona = new CursoPersona;
				$oCursoPersona->registrarCursoPersona($registrar[0],$us[0],$us[1],$oConexion);
			}
		}
		if($registrar !== false){
			return true;
		}
		else{
			return false;
		}
    } //Termina funcion registrarCursosFecha()
	public function eliminarCursoFechaPorId($cursoFechaId){
		$oConection = new conectorDB; //instanciamos conector
		$query = "DELETE FROM curso_fechas WHERE id = :id ";
		//VALORES PARA REGISTRO
		$values = array("id"=>$cursoFechaId);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
}/// TERMINA CLASE CURSO FECHA ///

class Fechas{
	private $fecha;
	public function obtenerFecha(){
		$consulta = "SELECT * FROM fechas";
		$valores = null;	
		$oConectar = new conectorDB; //instanciamos conector
		$this->fecha = $oConectar->consultarBD($consulta,$valores);
		return $this->fecha;
	} //Termina funcion obtenerFechas();
	public function obtenerFechasPorIdCursoFecha($cursofecha){
		$consulta = "SELECT * FROM fechas WHERE curso_fechas_id = ".$cursofecha;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursos = $oConectar->consultarBD($consulta,$valores);
		return $this->cursos;
	} //Termina funcion obtenerFechasPorIdCurso();
	public function obtenerFechasPorFecha($fecha){
		$consulta = "SELECT f.id fid, f.fecha, cf.id curso_fechas_id, cf.curso_id, cf.nombre, cf.color
					FROM fechas f
					INNER JOIN curso_fechas cf ON cf.id = f.curso_fechas_id
					WHERE f.fecha = '".$fecha."' ";
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursos = $oConectar->consultarBD($consulta,$valores);
		return $this->cursos;
	} //Termina funcion obtenerFechasPorFecha();
	public function registarFechaCurso($curso_fechas_id,$fecha,$oConection){
		$query = "INSERT INTO fechas(curso_fechas_id,fecha)
					VALUES (:curso_fechas_id, :fecha)";
		//VALORES PARA REGISTRO
		$values = array("curso_fechas_id"=>$curso_fechas_id,
						"fecha"=>$fecha);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
	public function eliminarFechaOCursoPorFechaId($fecha_id){
		$consulta = "SELECT f.*, (select count(*) from fechas where fechas.curso_fechas_id = f.curso_fechas_id) as c
					FROM fechas f
					WHERE f.id = ".$fecha_id;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$fechas = $oConectar->consultarBD($consulta,$valores);
		
		if(@$fechas[0]['c']==1){
			$cp = new CursoPersona;
			$cp->eliminarParticipantesPorCursoFechaId($fechas[0]['curso_fechas_id']);
			
			$cf = new CursoFecha;
			$cf->eliminarCursoFechaPorId($fechas[0]['curso_fechas_id']);
		}
		$oConection = new conectorDB; //instanciamos conector
		$query = "DELETE FROM fechas WHERE id = :id ";
		//VALORES PARA REGISTRO
		$values = array("id"=>$fecha_id);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
	public function agregarFechaACursoPorCursoFechaId($curso_fechas_id,$fecha){
		$query = "INSERT INTO fechas(curso_fechas_id,fecha)
					VALUES (:curso_fechas_id, :fecha)";
		//VALORES PARA REGISTRO
		$values = array("curso_fechas_id"=>$curso_fechas_id,
						"fecha"=>$fecha);
		$oConection = new conectorDB; //instanciamos conector
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
}

class CursoPersona{
	private $cursopersonas;
	public function obtenerPersonas(){
		$consulta = "SELECT * FROM curso_persona";
		$valores = null;	
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursopersonas = $oConectar->consultarBD($consulta,$valores);
		return $this->cursopersonas;
	} //Termina funcion obtenerPersonas();
	public function obtenerPersonasPorIdCursoFecha($cursofecha){
		$consulta = "SELECT * FROM curso_persona WHERE curso_fechas_id = ".$cursofecha;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursopersonas = $oConectar->consultarBD($consulta,$valores);
		return $this->cursopersonas;
	} //Termina funcion obtenerPersonasPorIdCurso();
	public function registrarCursoPersona($curso_fechas_id,$personaId,$participantes,$oConection){
		$query = "INSERT INTO curso_persona(curso_fechas_id,persona_id,participantes)
					VALUES (:curso_fechas_id, :persona_id, :participantes)";
		//VALORES PARA REGISTRO
		$values = array("curso_fechas_id"=>$curso_fechas_id,
						"persona_id"=>$personaId,
						"participantes"=>$participantes,
						);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
	public function actualizarParticipantes($id,$participantes){
		$oConection = new conectorDB; //instanciamos conector
		$query = "UPDATE curso_persona SET participantes = :participantes
					WHERE id = :id ";
		//VALORES PARA REGISTRO
		$values = array("participantes"=>$participantes,
						"id"=>$id
						);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
	public function eliminarParticipantesPorCursoFechaId($cursoFechaId){
		$oConection = new conectorDB; //instanciamos conector
		$query = "DELETE FROM curso_persona WHERE curso_fechas_id = :curso_fechas_id ";
		//VALORES PARA REGISTRO
		$values = array("curso_fechas_id"=>$cursoFechaId);
		$result = $oConection->consultarBD($query, $values);
		return $result;
	}
	public function obtenerParticipantesPorCursoFechaId($cursofechaid){
		$consulta = "SELECT sum(participantes) participantes FROM curso_persona WHERE curso_fechas_id = ".$cursofechaid;
		$valores = null;
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursopersonas = $oConectar->consultarBD($consulta,$valores);
		return $this->cursopersonas;
	} //Termina funcion obtenerPersonasPorIdCurso();
}
