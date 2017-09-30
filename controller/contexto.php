<?php

//program's richy's 2017 
class contexto
{
	var $modelo;
	public function __construct(IModelo $modelo){

		$this->modelo=$modelo;
	}
	
	

	//metodos generales de la peticion de REQUEST nediabte http
	public function get($data){
		if(count($data)!=2)
			return self::obtener();
		else
			return self::obtener($data[0],$data[1]);

	}

	
	public function post($data){
		
		$url=file_get_contents('php://input');
		$data_array=json_decode($url);


		if(empty($data[0]))
			return self::procesar($data_array);
		else
			return self::procesar($data_array,$data[0]);
	}

	public function put(){
		$url=file_get_contents('php://input');
		$data_array=json_decode($url);
		return self::actualizar($data_array);
			
	}
	public function delete(){
		$url=file_get_contents('php://input');
		$data_array=json_decode($url);
		return self::eliminar($data_array);		
	}



	//implementacion de la estrategia


	public function eliminar($_array_data){
		return $this->modelo->eliminar($_array_data);
	} 
	public function actualizar($_array_data){
		return $this->modelo->actualizar($_array_data);
	}
	public function obtener($_campo=null,$_dato=null){
		return $this->modelo->obtener($_campo,$_dato);	
	}
	public function procesar($_array_data){
		return $this->modelo->procesar($_array_data);
	}	
}

?>
