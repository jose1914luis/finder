<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales		
	require_once("Modelos/ReportGenerator.php");
	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ReportGenerator();
	$tablaSol	= "";
	$tablaTit	= "";
	
	$_SESSION["myExcelSolicitudesFile"] = "";
	$_SESSION["myExcelTitulosFile"] = "";	
	

	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	

	if(!empty($_POST)) {	
		$listadoSolicitudes = $reporte->selectSolicitudesConsultas($_POST["txt_CodigoExpediente"], $_POST["txt_Mineral"], $_POST["txt_Municipio"], $_POST["txt_Departamento"], $_POST["txt_Propietario"], $_POST["dt_RadicaDesde"], $_POST["dt_RadicaHasta"], $_POST["selModalidad"], $_POST["selEstadoJuridico"]);
		
		echo "<b>Formatos para descarga de archivos:</b>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitudes <a href='prospect.report.consulta.win.solicitudesExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de Solicitudes' height='35' width='35' border='0'></a>
				  
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T&iacute;tulos <a href='prospect.report.consulta.win.titulosExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de T&iacute;tulos' height='35' width='35' border='0'></a>			  
			<hr size='1'>";						
		
		if(!empty($listadoSolicitudes)){		
			$nroSolicitudes = sizeof($listadoSolicitudes);
			$nroColumnas = sizeof($listadoSolicitudes[0]) + 1;
					
			$tablaSol  = "<h3>Total Solicitudes: $nroSolicitudes</h3>";
			$tablaSol .= "<table border='1'><tr bgcolor='#EEEEEE'><td align='center' colspan='$nroColumnas'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SOLICITUDES</b></td></tr>";			
			$tablaSol .= "<tr><td align='center'><b>Reporte</b></td>";
		
			foreach($listadoSolicitudes[0] as $k=>$v)
				$tablaSol .= "<td align='center'><b>".$k."</b></td>";
			$tablaSol .= "</tr>";	
			
			for($i=0;$i<$nroSolicitudes;$i++) {
				$enlace = "<a href='javascript:' onclick=\"window.open('prospect.freeGeneratorArea.report.map.c.php?placa=".$listadoSolicitudes[$i]["placa"]."&clasificacion=SOLICITUD', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoSolicitudes[$i]["placa"]."'></a>";

				$tablaSol .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoSolicitudes[$i] as $k=>$v)
					$tablaSol .= "<td>".utf8_decode($v)."</td>";
				$tablaSol .= "</tr>";	
			}	
			$tablaSol .= "</table>";	
			$_SESSION["myExcelSolicitudesFile"] = $tablaSol;
		}
		
		$listadoTitulos = $reporte->selectTitulosConsultas($_POST["txt_CodigoExpediente"], $_POST["txt_Mineral"], $_POST["txt_Municipio"], $_POST["txt_Departamento"], $_POST["txt_Propietario"], $_POST["dt_OtorgaDesde"], $_POST["dt_OtorgaHasta"], $_POST["selModalidad"], $_POST["selEstadoJuridico"]);
		
		if(!empty($listadoTitulos)){
			$nroTitulos = sizeof($listadoTitulos);
			$nroColumnas = sizeof($listadoTitulos[0]) + 1;			

			$tablaTit  = "<hr size='1'><h3>Total T&iacute;tulos: $nroTitulos</h3>";
			$tablaTit .= "<table border='1'><tr bgcolor='#EEEEEE'><td align='center' colspan='$nroColumnas'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>TITULOS</b></td></tr>";			
			$tablaTit .= "<tr><td align='center'><b>Reporte</b></td>";
			

			foreach($listadoTitulos[0] as $k=>$v)
				$tablaTit .= "<td align='center'><b>".$k."</b></td>";
			$tablaTit .= "</tr>";	
			
			for($i=0;$i<$nroTitulos;$i++) {
				$enlace = "<a href='javascript:' onclick=\"window.open('prospect.freeGeneratorArea.report.map.c.php?placa=".$listadoTitulos[$i]["placa"]."&clasificacion=TITULO', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoTitulos[$i]["placa"]."'></a>";
				$tablaTit .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoTitulos[$i] as $k=>$v)
					$tablaTit .= "<td>".$v."</td>";
				
				$tablaTit .= "</tr>";	
			}		
			$tablaTit .= "</table>";
			$_SESSION["myExcelTitulosFile"] = $tablaTit;			
		}		
/*	
		$resultado = $empresa->insertAll($_POST);
		if($resultado != "ok")
			$msgError = "<script>alert('Error durante el proceso de guardado de la Empresa {$_POST["emp_nombre"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('La Empresa {$_POST["emp_nombre"]} a sido almacenada correctamente')</script>";
*/
	}

	
?>	

<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<title>Report Generator :: SIGMIN</title>
	</head>
	<body>
		<?php
			if($tablaSol != "") echo $tablaSol;
			else "No existen registros de solicitudes mineras.<ht size=1>";
			
			if($tablaTit != "") echo $tablaTit;
			else "No existen registros de t&iacute;tulos mineros.<ht size=1>";
		
			echo $msgError;
		?>
	</body>
</html>
