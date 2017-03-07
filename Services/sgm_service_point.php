<?php
	session_start();

	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');	


	require_once("../Acceso/Config.php"); // Definici�n de las variables globales	
	require_once("../Modelos/ReportGenerator.php");
	// require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php");
        header('Access-Control-Allow-Origin: *');

	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ReportGenerator();
	$tabla		= "";
	

	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	if(@trim($_GET["mbl_coords"])!=""&&@trim($_GET["mbl_origen"])!="") {				
		$listadoSuperposiciones = $reporte->getCoordsByOrigen(trim($_GET["mbl_coords"]),trim($_GET["mbl_origen"]));
		$listadoSuperposiciones["msg_error"] = "";
		
		if(empty($listadoSuperposiciones))
			$listadoSuperposiciones["msg_error"] = "No hay resultados a la consulta";
		
	} else 
		$listadoSuperposiciones["msg_error"] = "No fue remitida coordenada de consulta";
	
	echo json_encode($listadoSuperposiciones);

?>	
