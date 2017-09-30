<?php
require_once 'credenciales.php';

class conexion
{

	private static $instancia=null;
	private static $basedatos;


	final private function __construct(){}
	final private function __clone(){}

	
	public static function getInstancia()
	{
		if(self::$instancia===null)
			self::$instancia=new self();
		return self::$instancia;
	}

	public function getBD()
	{
		try{
			if(self::$basedatos==null)
			{
				
				$tns = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = ".HOST.")(PORT = 1521)))(CONNECT_DATA =(SERVICE_NAME = XE)))";
				
				self::$basedatos=new PDO("oci:dbname=".$tns,USSER,PASS);
				self::$basedatos->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			}
		
		}
		catch(PDOException $e){
			throw new ExceptionApi("error",$e->getMessage());
		}
		return self::$basedatos;
		
		
	}

	






	//*******************consultas T-SQL genericas******************//
	public function GenericQuery($_query,$_datas=null)
	{
		$conn=conexion::getInstancia()->getBD();
		$sentencia=$conn->prepare($_query);
		
		if(is_string($_datas)||!$_datas){
			if($_datas)
				$sentencia->bindParam(1,$_datas);	
		}
		else{
			$valores=array();
			foreach ($_datas as $key=>$value) 
				array_push($valores, $value);

			for($i=0;$i<sizeof($valores);$i++)
				$sentencia->bindParam($i+1,$valores[$i]);
		}		

		if($sentencia->execute())
			return $sentencia;
	}

	//**************************************************************//


	//*******************consultas T-SQL genericas******************//
	public function GenericQueryProcedure($_query,$_datas=null)
	{
		$conn=conexion::getInstancia()->getBD();
		$sentencia=$conn->prepare($_query);
		
		if(is_string($_datas)||!$_datas){
			if($_datas)
				$sentencia->bindParam(1,$_datas);	
		}
		else{
			$valores=array();
			foreach ($_datas as $key=>$value) 
				array_push($valores, $value);

			for($i=0;$i<sizeof($valores);$i++)
				$sentencia->bindParam($i+1,$valores[$i]);
		}		

		if($sentencia->execute())
			return true;
	}

	//**************************************************************//






	//**********************constltas T-SQL especificas*************//



	public function Qdelete($tabla,$filtro,$operador)
	{

		$conn=conexion::getInstancia()->getBD();
		$cuerpo="";
		foreach ($filtro as $key=>$value) {
			$cuerpo.=$key. "=".$value." ".$operador." ";
		}
		$cuerpo=trim($cuerpo,$operador." ");

		$comando="DELETE FROM ".$tabla." WHERE ".$cuerpo;
		$sentencia=$conn->prepare($comando);
		if($sentencia->execute())
			return $sentencia;
		
	}

	public function Qinsert($tabla,$datos)
	{

		$conn=conexion::getInstancia()->getBD();
		$campo="";
		$valor="";
		foreach ($datos as $key=>$value) {
			$campo.=$key.",";
			$valor.="'".$value."',";
		}
		$campo=trim($campo,",");
		$valor=trim($valor,",");

		$comando="INSERT INTO ".$tabla."(".$campo.") VALUES(".$valor.")";
		$sentencia=$conn->prepare($comando);
		if($sentencia->execute())
			return $sentencia;	
	}

	public function QinsertLote($tabla,$datos,$id)
	{

		$conn=conexion::getInstancia()->getBD();
		$campo="";
		$valor="";
		foreach ($datos as $key=>$value) {
			$campo.=$key.",";
			$valor.="'".$value."',";
		}
		$campo=trim($campo,",");
		$valor=trim($valor,",");

		$comando="INSERT INTO ".$tabla."(".$campo.") VALUES(".$valor.")";
		$sentencia=$conn->prepare($comando);
		if($sentencia->execute())
			return $sentencia;	
	}

	public function Qupdate($tabla,$datos)
	{

		$conn=conexion::getInstancia()->getBD();
		$campo="";
		
		//$sentencia=$conn->prepare($_query);
		$matriz=(array)$datos;
		$keys=array_keys($matriz);
		
		$iKey=array_shift($keys);
		$iValue=array_shift($matriz);

	

		foreach ($matriz as $key=>$value) {
			$campo.=$key."='".$value."',";
		}
		$campo=trim($campo,",");

		$comando="UPDATE ".$tabla." SET ".$campo." WHERE ".$iKey."='".$iValue."'";
		$sentencia=$conn->prepare($comando);
		if($sentencia->execute())
			return $sentencia;	
	}

	public function Qselect($tabla,$campos,$filtro,$operador){
		$conn=conexion::getInstancia()->getBD();
		$cuerpo="";
		if($filtro){
			foreach ($filtro as $key=>$value)
				$cuerpo.=$key. "='".$value."' ".$operador." ";

			$cuerpo=trim($cuerpo,$operador." ");
			
			$comando="SELECT ".$campos." FROM ".$tabla." WHERE ".$cuerpo;
		}
		else{
			$comando="SELECT ".$campos." FROM ".$tabla;
		}
		$sentencia=$conn->prepare($comando);
		if($sentencia->execute())
			return $sentencia;
	}


	//*************************************************************************//

	

	function __destruct()
	{
		self::$basedatos=null;
	}
}




?>
