<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/ReportGenerator.php");
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
	$generaURL		= new ControlPopups();

	
	if($_POST["coordenadasPry"]!="") {				
	
		$placa = $prospectoSGM->crearProspecto();
		$resultado = $prospectoSGM->insertAll($_POST, $Id_Empresa, $_SESSION["id_usuario"], $placa);
		$centroides = $prospectoSGM->getCentroideWGS84($placa);
		
		$areaPerimetro = $prospectoSGM->getArea($placa);
		$areaPoly = $areaPerimetro["area"];
		$perimetroPoly = $areaPerimetro["perimetro"];
		
		if($resultado == "OK") {
			$consultar = new ReportGenerator();
			$msgProceso = "<script>alert('Se ha generado el Codigo de Prospecto $placa')</script>";
			//$accionPage = new SeguimientosUsuarios;
			//$accionPage->generarAccion("Generacion del prospecto '$placa'");
			
			// Generación de análisis de superposiciones del prospecto
			$tipo_analisis = "PROSPECTO";
			$tipoEstudio = "ESTUDIO_TECNICO_PROSPECTO";
			$listadoEstudio = $consultar->selectEstudiosTecnicosProspectos($Id_Empresa, $placa);
				
			//$accionPage = new SeguimientosUsuarios;
			//$accionPage->generarAccion("Consulta Detallada en CMQ de Estudios Tecnicos: Para $tipo_analisis.");

			if(!empty($listadoEstudio)){		
				$nroSolicitudes = sizeof($listadoEstudio);
				$nroColumnas = sizeof($listadoEstudio[0]) + 1;
				
				$codAcceso = $generaURL->setControlPopup($placa, $tipoEstudio);				
				
				echo		"<hr size=1>Reporte de Area Libre: <a href='javascript:' onclick=\"window.open('http://www.sigmin.co/finder/prospect.freeGeneratorArea.report.map.c.php?cod_acceso=$codAcceso', 'pop2', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportResultArea.jpg' border='0' width='40' height='40' title='Generaci&oacute;n de Reporte de Area Libre para Prospecto $placa'></a>";
				
				echo 		"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				echo 		"Descarga de Archivo de Superposiciones: <a href='prospect.freeGeneratorArea.report.excelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Descarga de Archivo de Superposiciones en Formato Excel' height='35' width='35' border='0'></a>";								
				
				$tabla .=	"<hr size=1><h3>N&Uacute;MERO DE SUPERPOSICIONES: $nroSolicitudes</h3><hr size=1>";
				$tabla .=	"<table border='1'>";
				$tabla .= "<table border='1'><tr bgcolor='#EEEEEE'><td align='center' colspan='$nroColumnas'><b>REPORTE DE SUPERPOSICIONES</b></td></tr>";				
				$tabla .= 	"<tr><td align='center'><b>Reporte</b></td>";
			
				foreach($listadoEstudio[0] as $k=>$v)
					$tabla .= "<td align='center'><b>".utf8_decode($k)."</b></td>";
				$tabla .= "</tr>";	
				
				for($i=0;$i<$nroSolicitudes;$i++) {
					$codAcceso = $generaURL->setControlPopup($listadoEstudio[$i]["expediente_superpone"], $listadoEstudio[$i]["tipo_superposicion"]);
					$URL_Acceso = "http://www.sigmin.co/finder/reporteAreas.php?cod_acceso=$codAcceso";					
					
					$enlace = "<a href='javascript:' onclick=\"window.open('$URL_Acceso', 'pop4', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoEstudio[$i]["expediente_superpone"]."'></a>";
					
					$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
					foreach($listadoEstudio[$i] as $k=>$v)
						$tabla .= "<td>".utf8_decode($v)."</td>";
					//$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoEstudio[$i]["area_estudio"]."&tipoExpediente=ESTUDIO_".$tipo_analisis."' target='_blank' >Report</a></td>";
						
					$tabla .= "</tr>";	
				}		
				$tabla .= "</table>";	
				
				$_SESSION["myExcelFile"] = $tabla;
			} else {
				echo "
					<hr size='0'>
					<center><h1>No existen superposiciones<br>para el prospecto $placa</h1></center>
					<hr size='0'>
				";
			}
	
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
