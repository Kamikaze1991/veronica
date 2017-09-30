<?php


//program's richy's 2017 


class trackenvio implements IModelo
{


	//definicion de datos
	const TABLE_NAME="trackenvio";
	const ALL_FIELDS="ID,IDENVIO,IDTRANSPORTE,UBICACION";

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



				$_rs=conexion::getInstancia()->GenericQueryProcedure("call ACTUALIZAR_UBI_TRACK(?,?)",$array_info);	

				//$_rs=conexion::getInstancia()->Qinsert(self::TABLE_NAME,$_array_data);			
				//$_rs=conexion::getInstancia()->Qinsert(self::TABLE_NAME,$_array_data);
				if($_rs)
					return ["state"=>"sucess","content"=>$_rs];
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

			case 'ESTADO':
				$filtro=array($campo=>$dato);
				$_rs=conexion::getInstancia()->Qselect(self::TABLE_NAME,"ID,FECHA,CIUDADORIGEN,CIUDADDESTINO,ESTADO",(Object)$filtro,null);
				break;

			case 'PENDIENTE':
				$filtro=array($campo=>$dato);
				$_rs=conexion::getInstancia()->GenericQuery("SELECT trackenvio.id, envio.ciudadorigen,envio.ciudaddestino,envio.estado,trackenvio.ubicacion FROM trackenvio INNER JOIN envio on envio.id=trackenvio.idenvio WHERE envio.estado='despachado'",null);
				
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

				
				$_rs=conexion::getInstancia()->GenericQueryProcedure("call INGRESO_TRACK(?,?,?)",$_array_data);	

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

