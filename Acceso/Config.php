<?php
	/*
		Variables de configuraci�n de CMQ
	*/	
	
	$GLOBALS ["my_server"] 		= "localhost";		// Servidor donde se encuentra instalada la base de datos
	$GLOBALS ["my_database"] 	= "cmqpru";   		// Esquema de la base de datos
	$GLOBALS ["my_user"] 		= "cmqpru";			// Usuario de la base de datos
	$GLOBALS ["my_password"] 	= "2012zygMin";		// Contrase�a de la base de datos
	$GLOBALS ["my_port"]		= "5432";			// puerto de la base de datos a utilizar
	
													// url de pagina de error
	$GLOBALS ["url_error"]		= "http://www.sigmin.co/finderaccount/";				
	
	$GLOBALS ["db1"]  			= "host=".$GLOBALS ["my_server"]." port=".$GLOBALS ["my_port"]." dbname=".$GLOBALS ["my_database"]." user=".$GLOBALS ["my_user"]." password=".$GLOBALS ["my_password"]."";	
	// Variables globales para Document Management
	$GLOBALS ["docDigital"]		= "DigitalDocs/";	

	
	// funcion para evitar injeccion de codigo		
	function val_input($source) {
		if(empty($source)) return $source;
		if (is_array($source)) {
			foreach($source as $key => $value) {
				if(!empty($value)) 
					$source[$key] = htmlspecialchars(addslashes(stripslashes(strip_tags(trim($value)))));				
			}
			return $source;
		// clean this string
		} else 
			return htmlspecialchars(addslashes(stripslashes(strip_tags(trim($source)))));
	}	

	// Variable global del sitio web:
	$GLOBALS ["sitio"]			= "http://www.sigmin.co/";
	
	// Variables asociadas al uso de creditos en Account:	
	$GLOBALS ["SIGCoin_pesos"]			= 1000;	
	$GLOBALS ["SIGCoin_minimo"]			= 50000;
	$GLOBALS ["IdTipoServicio"]			= 8;
	$GLOBALS ["DescripcionServicio"]	= "ADQUISICION SERVICIOS SIGMIN";

	// Producci�n - Pagos por internet:
	
	$GLOBALS ["POST"]			= "https://gateway.payulatam.com/ppp-web-gateway/";	
	
	$GLOBALS ["Api_Key"] 		= "C9QjbZh3zQvTxzn1MO6mE34FY6";
	$GLOBALS ["Api_Login"]		= "B4oi4ZCKKEO2ds9";
	$GLOBALS ["Llave_Pub"]		= "PKmo6QiwR45GE5gTzS5KP2EJ2R";		
	$GLOBALS ["Comercio"]		= "544477";		
	$GLOBALS ["Pais"]			= "546641";   // Equivalente acountId
	$GLOBALS ["tax"]			= 0.19; // definicion del IVA. null=16% 2016<=, 19%:2017-2018;
	$GLOBALS ["taxReturnBase"]	= "";
	$GLOBALS ["currency"]		= "COP";
	$GLOBALS ["buyerEmail"]		= "pagosonline@sigmin.com.co";
	$GLOBALS ["test"]			= "0";
	$GLOBALS ["urlRespuesta"]	= $GLOBALS ["sitio"]."PagosOnline/pagosRespuesta.php";
	$GLOBALS ["urlConfirma"]	= $GLOBALS ["sitio"]."PagosOnline/pagosConfirmacion.php";

	$GLOBALS ["anio_aprob_res"]	= "2016";
	$GLOBALS ["prefijo"]		= "F";
	$GLOBALS ["ebill_token"]	= "6f77d0c3-6532-45f8-b5d1-f2aa6775d2a8"; // pruebas:  E7E3CF5B-7AB2-4EB6-ACB6-ACD2650CE443 
	$GLOBALS ["resol_fact"]		= "110000668552"; // pruebas: 320001235123   De SIGMIN es: 110000668552  
	$GLOBALS ["nit_empresa"]	= "900574173"; // pruebas: 900306824		De SIGMIN es: 900574173   
	$GLOBALS ["ebill_iva"]		= "19"; //  2016<=:16% 2017:19% 
	$GLOBALS ["ebill_control"]	= "1"; // 1: control de no repeticion de factura  OJO=> Produccion --> Cambiar a 1
	$GLOBALS ["ebill_enviomail"]= "1"; // 1: env�o de factura de parte de ebill 

	// variables de paginaci�n	
	$GLOBALS ["max_por_pagina"]	= 10;
	$GLOBALS ["pags_pantalla"]	= 5;
	
/*	
	// Evitar codigo intruso
	if(!empty($_POST)) 		$_POST = val_input($_POST);
	if(!empty($_GET))  		$_GET  = val_input($_GET);	
*/	
?>	
