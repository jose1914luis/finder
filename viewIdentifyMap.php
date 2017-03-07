<?php
	session_start();
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ReportGenerator.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 
	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ReportGenerator();
	$tabla		= "";
	$generaURL		= new ControlPopups();	
	
	$_SESSION["myExcelFile"] = "";
	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	if($_POST["coordenadasRAC"]!="") {				
		$listadoSuperposiciones = $reporte->getIdentify($_POST["coordenadasRAC"]);
		
		if(!empty($listadoSuperposiciones)){		
			$nroSuperposiciones = sizeof($listadoSuperposiciones);
			$nroColumnas = sizeof($listadoSuperposiciones[0]) + 1;
			$tabla .= "<table border='1' class='tableResult'><tr><th align='left' colspan='$nroColumnas'  class='tableHead'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>IDENTIFY - </b>N&uacute;mero de Registros:  $nroSuperposiciones</th></tr>";
			$tabla .= "<tr><th align='center' class='tableResult'><b>REPORT</b></th>";
		
			foreach($listadoSuperposiciones[0] as $k=>$v)
				$tabla .= "<th align='center' class='tableResult'><b>".strtoupper(str_replace("_", " ", $k))."</b></th>";
			$tabla .= "</tr>";	
			
			for($i=0;$i<$nroSuperposiciones;$i++) {
				$codAcceso = $generaURL->setControlPopup($listadoSuperposiciones[$i]["placa"], $listadoSuperposiciones[$i]["tipo_expediente"]);
				$URL_Acceso = "http://www.sigmin.co/finder/reporteAreas.php?cod_acceso=$codAcceso";
				
				
				$enlace = "<a href='javascript:' onclick=\"window.opener.parent.cambiarExpediente('".$listadoSuperposiciones[$i]["placa"]."', '".$listadoSuperposiciones[$i]["tipo_expediente"]."')\"><img src='Imagenes/verEnMapa.jpg' border='0' width='30' height='30' title='Ubicacion Geogr&aacute;fica Expediente  ".$listadoSuperposiciones[$i]["placa"]."'></a>&nbsp;<a href='javascript:' onclick=\"window.open('$URL_Acceso', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoSuperposiciones[$i]["placa"]."'></a>";
				$tabla .= "<tr class='tableResult'><td align='center'><b>$enlace</b></td>";
				foreach($listadoSuperposiciones[$i] as $k=>$v)
					$tabla .= "<td>".($v)."</td>";
				$tabla .= "</tr>";	
			}	
			$tabla .= "</table>";	
		}
	} else {
		$msgProceso = "
					<script>
						alert(':: Identify - No data found'); 
						close();
					</script>";
	}

	
?>	

<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<link href="Javascript/formatosResultados.css" type="text/css" rel="stylesheet">		
	<title>:: SIGMIN - Identify</title>
	</head>
	<body>	
		<?php
			if($tabla != "") echo $tabla;		
			echo $msgError;
		?>
	</body>
</html>
