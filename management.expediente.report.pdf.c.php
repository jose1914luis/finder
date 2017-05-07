<?php
	session_start();

	require_once("Acceso/Config.php"); // Definici�n de las variables globales	
	require_once("Modelos/SeguimientosUsuarios.php");
	require_once("Modelos/DocumentosPlantillas.php");	
	require_once("Modelos/Usuarios_SGM.php");
	
	// validaci�n de usuarios en CMQ
	$validate 	= new Usuarios_SGM();	
	$validate->validaAccesoPagina($_SESSION["usuario_sgm"], $_SESSION["passwd_sgm"]);
		
	$msgAcceso = "";
	
	if (!empty($_POST["txtPathFile"]) && @$_POST["txtPathFile"] != "") {		
		$documento 	= new DocumentosPlantillas();
		$rutaDoc	= $GLOBALS ["docDigital"]."/".$documento->getPathArchivoByNombre($_POST["txtPathFile"]);

		$accionPage = new SeguimientosUsuarios;
		$accionPage->generarAccion("Archivo PDF Consultado - $rutaDoc");
				
		header('Content-Type: application/pdf');
		//header('Content-Disposition: attachment; filename="'.$_SESSION["txtPathFile"].'"');
		readfile($rutaDoc);
	} else
		header("Location: DocumentosElectronicos/r2d2DocumentManagement.pdf"); 


	
?>	

