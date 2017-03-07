<?php

	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/ProspectosBogSGM.php");
	//require_once("Modelos/SeguimientosUsuarios.php");	
	//require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	// variables del controlador	
	$msgError = "";
	$Id_Empresa = 2;  // pendiente de volver variable global

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>Generar Area Libre</title>
		<!--
		<link rel="stylesheet" href="Javascript/style1.css" type="text/css">
		<link rel="stylesheet" href="Javascript/google.css" type="text/css">
		<link rel="stylesheet" href="Javascript/style2.css" type="text/css">
		-->
		
		<!-- this gmaps key generated for http://openlayers.org/dev/ -->
		<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM'></script>
		
		<script src="http://dev.openlayers.org/OpenLayers.js"></script>


		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>	
		<script type="text/javascript">

			var map, drawControls, polygonFeature, vectorLayer;
			var CONTAR_POLY = 0;
			var GLOBAL_POLY;

			OpenLayers.IMAGE_RELOAD_ATTEMPTS = 2;
			OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

			function init() {
				$.post('viewServicesSIGMIN.php', { loadService : true}, function(resp) { if(resp!="") eval(resp); else alert("falla al cargar los servicios geográficos");});	
            }

            function toggleControl() {				
				if(CONTAR_POLY) {
					drawControls["polygon"].activate();				
					if (typeof GLOBAL_POLY != "undefined") {
						GLOBAL_POLY.removeAllFeatures();
						CONTAR_POLY = 0;
					}					
				}
				else {
					drawControls["polygon"].activate();	
				}
				document.forms[0].boton.value = "Dibujar Poligono";
            }
			
			function clearFields() {
				GLOBAL_POLY.removeAllFeatures();
				document.forms[0].reset();
				document.forms[0].boton.value = "Dibujar Poligono";
			}
			
			function validarPlaca(campoPlaca) { 
				$.post('viewValidaPlaca.php', { CoordenadasPry : document.forms[0].coordenadasPry.value, CodExpediente: document.forms[0].txtCodigoExpediente.value}, function(resp) {
						if(resp!="") 
							eval(resp);
						else
							alert("No hay retorno de información");
					});				
			}

		</script>	
	</head>	
	<body onload="init()">
		<p>
		<form method="post" target="pop" action="prospect.freeGeneratorArea.report.c.php">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;AN&Aacute;LISIS DE AREA LIBRE&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td width="5%">&nbsp;</td>
				<td>Ingrese el pol&iacute;gono sobre el cual desea evaluar superposici&oacute;n: </td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>					
					<!-- Ubicación del panel de mapa -->
					<div id="map" style='width: 100%; height: 400px; border: 0px;'></div>
					<input type="hidden" name="coordenadasPry" value="" id="coordenadasPry"/>					
					<hr size=1/>
					<input type="button" name="boton" value="Dibujar Poligono" onclick="toggleControl()">
				</td>
			</tr>							
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="button" value="Generar Reporte de Superposici&oacute;n" onclick="window.open('', 'pop', 'width=800,height=600, resizable=yes, scrollbars=yes'); document.forms[0].submit();return false;">
					<hr size=1/>
				</td>
			</tr>								
		</table>
		</form>
		<?php
			if($msgError)
				echo $msgError;
		?>			
	</body>
</html>
