<?php


//program's richy's 2017 


class puesto implements IModelo
{


	//definicion de datos
	const TABLE_NAME="puesto";
	const ALL_FIELDS="ID,NOMBRE,DESCRIPCION";

	//*********************************procedimiento del DELETE***********************************//
	//********************************************************************************************//
	//********************************************************************************************//
	public function eliminar($array_info){
		try{
			if($array_info){
				$_rs=conexion::getInstancia()->Qdelete(self::TABLE_NAME,$array_info,null);
				if($_rs)
					return ["state"=>"sucess","content"=>$_rs->rowCount()];
				else
					return ["state"=>"error","content"=>"algo salio mal"];
			}
			else{
				return ["state"=>"error","content"=>"no hay valores"];
			}
		
		}
		catch(PDOException $e){
			throw new ExceptionApi("error","error interno eliminacion");
		}		
	}

	//************************************fin del procedimiento***********************************//
	//********************************************************************************************//
	//********************************************************************************************//





	//*********************************procedimiento del PUT**************************************//
	//********************************************************************************************//
	//********************************************************************************************//


	public function actualizar($array_info){	
	
		try{
			$Tarray=null;
			if($array_info){



				//$_rs=conexion::getInstancia()->GenericQuery("SELECT ".self::ALL_FIELDS." FROM ".self::TABLE_NAME." where id=?",$array_info->id);
				$filtro=array("ID"=>$array_info->ID);
				$_rs=conexion::getInstancia()->Qselect(self::TABLE_NAME,self::ALL_FIELDS,(Object)$filtro,null);
				
				
				
				if($_rs)
					$Tarray=$_rs->fetch(PDO::FETCH_ASSOC);
				//recorremos el array de parametros y lo rellenamos con el array de resultados
				foreach($array_info as $campo=>$valor)
					$Tarray[$campo]=$valor;

				//array_shift($Tarray);
				//echo json_encode($Tarray);
				$Tarray=(Object)($Tarray);	
				$_rs=conexion::getInstancia()->Qupdate(self::TABLE_NAME,$Tarray);

				if($_rs)
					return ["state"=>"sucess","content"=>$_rs->rowCount()];
				else
					return ["state"=>"error","content"=>"no se actualzo"];
				
			}
			else{
				return ["state"=>"error","content"=>"parametros ausentes"];
			}
		}
		catch(PDOException $e){
			throw new ExceptionApi("error",$e);
		}	
	}

	//************************************fin del procedimiento***********************************//
	//********************************************************************************************//
	//********************************************************************************************//







	//*********************************procedimiento del GET**************************************//
	//********************************************************************************************//
	//********************************************************************************************//

	public function obtener($campo=null,$dato=null){
		

		switch($campo){
			case 'ID':
				$filtro=array($campo=>$dato);
				$_rs=conexion::getInstancia()->Qselect(self::TABLE_NAME,self::ALL_FIELDS,(Object)$filtro,null);
				break;

			default:
				$_rs=conexion::getInstancia()->Qselect(self::TABLE_NAME,self::ALL_FIELDS,null,null);
				break;
		}

		if($_rs){
			return ["state"=>"sucess","content"=>$_rs->fetchAll(PDO::FETCH_ASSOC)];
		}
		else{
			return ["state"=>"error","content"=>null];
			
		}
	}

	


	//************************************fin del procedimiento***********************************//
	//********************************************************************************************//
	//********************************************************************************************//




	
	//*********************************procedimiento del POST*************************************//
	//***********************************este mecanismo es especial*******************************//
	//********************procedimientos especiales del objeto y de insercion*********************//





	//************************mecanismo para seleccionar la naturaleza del post*******************//

	public function procesar($array_info){
	
		$rs=null;
		switch($array_info->accion){
			case 'save':
				$rs=self::crear($array_info->data);
				break;
			default:
				$rs=self::crear($array_info->data);
				break;
		}


		if(!$rs){
			return ["state"=>"error","content"=>null];
		}
		else{	
			http_response_code(200);
			return ["state"=>"sucess","content"=>$rs];
		}
	}

	//*********************************************************************************************//




	//*********************************mecanismos originales***************************************//

	private function crear($_array_data)
	{
	
		try{
			
			if($_array_data)
			{
				
				$_rs=conexion::getInstancia()->GenericQueryProcedure("call INGRESO_PUESTO(?,?)",$_array_data);	

				//$_rs=conexion::getInstancia()->Qinsert(self::TABLE_NAME,$_array_data);			
				//$_rs=conexion::getInstancia()->Qinsert(self::TABLE_NAME,$_array_data);
				if($_rs)
					return $_rs;
			}
		}
		catch(PODException $e){
			throw new ExceptionApi("error","problemas internos");
		}
	}





	//************************************fin del procedimiento***********************************//
	//********************************************************************************************//
	//********************************************************************************************//
	
}
?>

