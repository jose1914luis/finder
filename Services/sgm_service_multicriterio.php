<?php
	session_start();

	 error_reporting(E_ALL);
	 ini_set('display_errors', '1');	


	require_once("../Acceso/Config.php"); // Definici�n de las variables globales	
	require_once("../Modelos/IndexacionesQueries.php");
	// require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php");
        
        header('Access-Control-Allow-Origin: *');

	
	// variables del controlador	
	$msgError 	= "";
	//$reporte 	= new ReportGenerator();
	$tabla		= "";
	

	if(@trim($_GET["multicriterio"])!="") {	
		$res = new IndexacionesQueries(); 
		$listaExpedientesResults["solicitudes"] 	= $res->selectSolicitudesByListaCampos($_GET["multicriterio"]);
		$listaExpedientesResults["titulos"]			= $res->selectTitulosByListaCampos($_GET["multicriterio"]);

		$listaExpedientesResults["msg_error"] = "";
		
		if(empty($listaExpedientesResults))
			$listaExpedientesResults["msg_error"] = "No hay resultados a la consulta";
		
	} else 
		$listaExpedientesResults["msg_error"] = "No hay datos para consultar";
	
	echo json_encode($listaExpedientesResults);
