<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definici�n de las variables globales	
//	require_once("Modelos/SeguimientosUsuarios.php");	
//	require_once("Modelos/Usuarios.php");
	
	// validaci�n de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
	$msgAcceso = "";
	
	if (isset($_POST["txtPathFile"]) && $_POST["txtPathFile"]!="") {
		header("Location: ../../CMQ_Pruebas/IDB/".$_POST["txtPathFile"]); 
	} 
		//header("Location: DocumentosElectronicos/r2d2DocumentManagement.pdf"); 
	
?>	

