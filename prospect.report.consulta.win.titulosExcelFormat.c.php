<?php

	session_start();

//	require_once("Acceso/Config.php");
	//require_once("Modelos/SeguimientosUsuarios.php");	
	//require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);	

	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=ReporteTitulos_".@date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		


	$excelFile = str_replace("<a href","<!-- <a href", $_SESSION["myExcelTitulosFile"]);
	$excelFile = str_replace("</a>","</a> -->",$excelFile);
	
	echo $excelFile;

?>	