<?php
	session_start();
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/AnalisisVecinos.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 	
	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ProspectosBogSGM();
	$vecino		= new AnalisisVecinos();
	$generaURL	= new ControlPopups();	
	$tabla 		= "";
	$idVecino	= 0;
	$idEmpresa	= 2;
	//$_SESSION["myExcelFile"] = "";
	

	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	

	if($_POST["coordenadasRAC"]!="") {
		
		$idVecino = $vecino->selectIdVecino();
		$errVecino = $vecino->insertAll($idVecino, $idEmpresa, "BOGOTA", $_POST["coordenadasRAC"]);
		
		if($errVecino != "OK") echo "<hr>$errVecino<hr>";
		
		$listadoSuperposiciones = $reporte->get_SuperposicionByNeighbor($idVecino);
		
		if(!empty($listadoSuperposiciones)){		
			$nroSuperposiciones = sizeof($listadoSuperposiciones);
			$nroColumnas = sizeof($listadoSuperposiciones[0]) + 1;
			
			echo 		"<hr size=1><b>Formatos para descarga de archivo:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='prospect.neighborAnalysis.Report.excelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Descarga de Archivo en Formato Excel' height='35' width='35' border='0'></a><hr size='1'>";				
			
			
			$tabla ="<h3>Total Superposiciones: $nroSuperposiciones</h3>"; 
			$tabla .= "<table border='1'><tr bgcolor='#EEEEEE'><td align='center' colspan='$nroColumnas'><b>ANALISIS DE VECINOS MAS CERCANOS</b></td></tr>";
			$tabla .= "<tr><td align='center'><b>Reporte</b></td>";
		
			foreach($listadoSuperposiciones[0] as $k=>$v)
				$tabla .= "<td align='center'><b>".$k."</b></td>";
			$tabla .= "</tr>";	
			
			for($i=0;$i<$nroSuperposiciones;$i++) {
				$codAcceso = $generaURL->setControlPopup($listadoSuperposiciones[$i]["expediente_superpone"], $listadoSuperposiciones[$i]["tipo_superposicion"]);
				$enlace = "<a href='javascript:' onclick=\"window.open('reporteAreas.php?cod_acceso=$codAcceso', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoSuperposiciones[$i]["expediente_superpone"]."'></a>";
				$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoSuperposiciones[$i] as $k=>$v)
					$tabla .= "<td>".utf8_decode($v)."</td>";
				$tabla .= "</tr>";	
			}	
			$tabla .= "</table>";	
			// $_SESSION["myExcelFile"] = $tabla;
		}
	} else {
		echo $msgProceso = "<script>
								alert('::ERROR:: No existe punto de influencia para evaluar'); 
								close();
							</script>";
	}

	
?>	

<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	</head>
	<body>
		<?php
			if($tabla != "") echo $tabla;
		
			echo $msgError;
		?>
	</body>
</html>
