<?php
//herramientas necesarias
require_once 'tools/ExceptionApi.php';
require_once 'data/credenciales.php';
require_once 'data/conexion.php';
require_once 'vistas/VistaXML.php';
require_once 'vistas/VistaJson.php';

//patron estretegia//
require_once 'controller/IModelo.php';
require_once 'controller/contexto.php';

//estrategias concretas
require_once 'controller/cliente.php';
require_once 'controller/telefonocliente.php';
require_once 'controller/transporte.php';
require_once 'controller/categoria.php';
require_once 'controller/puesto.php';
require_once 'controller/responsable.php';
require_once 'controller/envio.php';
require_once 'controller/trackenvio.php';



ob_clean(); //limpiamos la pantalla para no tener espacios

//require_once 'controller/producto.php';

$allcrud=array('cliente','telefonocliente','transporte','categoria','puesto','responsable','envio','trackenvio');

$formato = isset($_GET['formato']) ? $_GET['formato'] : 'json';

//$formato='json';

switch ($formato) {
    case 'xml':
        $vista = new VistaXML();
        break;
    case 'json':
    default:
        $vista = new VistaJson();
}






//**********manipulador de excepciones********************************//
set_exception_handler(function($e){
	$cuerpo=array("state"=>$e->state,"message"=>$e->getMessage());
	//echo json_encode($cuerpo,JSON_PRETTY_PRINT);
	$vista = new VistaJson();
	$vista->imprimir($cuerpo);

});

//********************************************************************//





//********obtenemos el Path Relativo y creamos una array***************//
$peticion=null;
if(isset($_GET['PATH_INFO']))
	$peticion=explode('/',$_GET['PATH_INFO']);
//*********************************************************************//




//********vomo norma general el primer elemento sera el crud***********//
$crud=null;
if($peticion)
	$crud=array_shift($peticion);
else
	$crud=null;
//*********************************************************************//



//********************para las consultas individuales*******************//
$peticion=array();
if(isset($_GET['campo'])&&isset($_GET['valor']))
	array_push($peticion, $_GET['campo'],$_GET['valor']);
else
	$peticion=null;








//****************mecanismo que permite validar si un recurso existe**//

if(!in_array($crud,$allcrud))
	throw new ExceptionApi("error","desconocido");
//********************************************************************//




//********************obtenemos el metodo de la request***************//
$metodo=strtolower($_SERVER['REQUEST_METHOD']);
//********************************************************************//





//*creacion del crud mediante patron estrategia***********************//
$$crud=new contexto(new $crud);
//********************************************************************//

try
{
	switch($metodo)
	{


	case 'get':
	case 'put':
	case 'post':
	case 'delete':
		if(method_exists($$crud,$metodo)){	//verificamos si el metodo existe en el contexto. 
			$_result=call_user_func(array($$crud,$metodo),$peticion); //llamamos al metodo de la funcion contexto.
			$vista->imprimir($_result);	

			
			
		}
		else{
			throw new ExceptionApi("error","error interno, no existen los metodos de servidor");
		}
		break;
	default:
		throw new ExceptionApi("error","Error no se ha escogido ningun metodo");
		break;
	}

}
catch(PDOException $e){
	
	throw new ExceptionApi("error",$e->getMessage());
}


?>