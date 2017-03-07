<?php

	session_start();

//	require_once("Acceso/Config.php");
	//require_once("Modelos/SeguimientosUsuarios.php");	
	//require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
	header('Content-type: application/vnd.ms-excel');	
	header("Content-Disposition: attachment; filename=ReporteSolicitudes_".@date("Ymd_His").".xls");	
	header("Pragma: no-cache");
	header("Expires: 0");		

	$excelFile = str_replace("<a href","<!-- <a href", $_SESSION["myExcelSolicitudesFile"]);
	$excelFile = str_replace("</a>","</a> -->",$excelFile);
	echo $excelFile;	

?>	