<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/ReportGenerator.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/Alertas.php");	
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 			
	//require_once("Modelos/SeguimientosUsuarios.php");	
	//require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	// variables del controlador	
	$msgError 	= "";
	$tabla 		= "";
	$Id_Empresa = $_SESSION["idEmpresa"];  
	$_SESSION["myExcelFile"] = "";
	
	$prospectoSGM = new ProspectosBogSGM();
	$monitoreo		= new Alertas();
	$generaURL		= new ControlPopups();	
	
	if($_POST["coordenadasPry"]!="") {				
	
		$placa = $prospectoSGM->crearProspecto();
		
		$resultado = $prospectoSGM->insertWithFreeZoneAlarm($_POST, $Id_Empresa, $_SESSION["usuario_sgm"], $_SESSION["id_usuario"]);
		$centroides = $prospectoSGM->getCentroideWGS84($placa);
		
		$areaPerimetro = $prospectoSGM->getArea($placa);
		$areaPoly = $areaPerimetro["area"];
		$perimetroPoly = $areaPerimetro["perimetro"];
		
		
		if($resultado == "OK") {
			$consultar = new ReportGenerator();
			$msgProceso = "<script>alert('Se ha generado el Codigo de Prospecto $placa')</script>";
			$res = $monitoreo->montoreoArchivoExpedientes($placa);
			//$accionPage = new SeguimientosUsuarios;
			//$accionPage->generarAccion("Generacion del prospecto '$placa'");

			// Inclusión del reporte de área que generará alerta
			$tipoEstudio = "PROSPECTO";		
			
			$codAcceso = $generaURL->setControlPopup($placa, $tipoEstudio);
			$URL_Acceso = "http://www.sigmin.co/finder/reporteAreas.php?cod_acceso=$codAcceso";

			// Aqui enviar correo electrónico al usuario cliente
			header("location: $URL_Acceso");
	
		} else 
			$msgProceso = " <script>
								alert('::ERROR:: El prospecto $placa no fue generado satisfactoriamente');
								close();
							</script>";			
	} else
		echo $msgProceso = "<script>
								alert('::ERROR:: No existe prospecto de referencia para evaluar'); 
								close();
							</script>";

	echo $tabla.$msgProceso;
	
?>	
