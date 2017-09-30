<?php

interface IModelo
{
	//mecanismos genericos para el REST
	public function obtener($campo=null,$dato=null);
	public function procesar($array_info);
	public function eliminar($array_info);
	public function actualizar($array_info);
}


?>
