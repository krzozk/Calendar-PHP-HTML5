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
		$consulta = "SELECT * FROM cursos_fechas";
		$valores = null;
		
		$oConectar = new conectorDB; //instanciamos conector
		$this->cursosFecha = $oConectar->consultarBD($consulta,$valores);
        
		return $this->cursosFecha;
	} //Termina funcion obtenerCursos();
	public function registrarCursosFecha($curso_id,$color,$fechas){
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
		if($registrar !== false){
			return true;
		}
		else{
			return false;
		}
    } //Termina funcion registrarCursos()
}/// TERMINA CLASE CURSO FECHA ///
