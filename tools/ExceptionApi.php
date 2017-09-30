<?php

class ExceptionApi extends Exception
{
	public $state=null;
	public function __construct($state,$message,$code=400)
	{
		$this->state=$state;
		$this->message=$message;
		$this->code=$code;
	}
	
}

?>
