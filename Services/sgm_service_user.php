<?php
	session_start();

	error_reporting(E_ALL);
	ini_set('display_errors', '1');	

	require_once("../Acceso/Config.php"); // Definici�n de las variables globales	
	require_once("../Modelos/Usuarios_SGM.php");
        
        header('Access-Control-Allow-Origin: *');
	
	// variables del controlador	
	$respuestaValidacion["estado_acceso"] = "***";

	if (@$_GET["login_user"]!="" && @$_GET["verification_code"]!="") { 
		$validate = new Usuarios_SGM();
		$respuestaValidacion["estado_acceso"] = $validate->validaPasswdMobile($_GET["login_user"], $_GET["verification_code"]);
	}  
	echo json_encode($respuestaValidacion);

?>	
