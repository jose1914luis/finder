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
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>Crear Proyecto</title>
		<!-- this gmaps key generated for http://openlayers.org/dev/ -->
		<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM'></script>
		<script src="http://dev.openlayers.org/OpenLayers.js"></script>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>	
		<script type="text/javascript">
			var map, drawControls, polygonFeature, vectorLayer, polygonLayer;
			var CONTAR_POLY = 0;
			var GLOBAL_POLY;			

			OpenLayers.IMAGE_RELOAD_ATTEMPTS = 2;
			OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

			function init() {
				$.post('viewServicesNeighborSIGMIN.php', { loadService : true}, function(resp) { if(resp!="") eval(resp); else alert("falla al cargar los servicios geográficos");});				
								
            }

            function toggleControl() {
				
				if(CONTAR_POLY) {
					
					drawControls["point"].activate();				
					if (typeof GLOBAL_POLY != "undefined") {
						GLOBAL_POLY.removeAllFeatures();
						polygonLayer.removeAllFeatures();
						CONTAR_POLY = 0;
					}					
				}
				else {
					drawControls["point"].activate();	
				}				
			
				document.forms[0].boton.value = "Dibujar Poligono";
            }
			
			function clearFields() {
				GLOBAL_POLY.removeAllFeatures();
				document.forms[0].reset();
				document.forms[0].boton.value = "Dibujar Poligono";
			}

		</script>	
	</head>	
	<body onload="init()">
		<p>
		<form method="post" target="pop" action="prospect.neighborAnalysis.Report.c.php">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;AN&Aacute;LISIS DE VECINO M&Aacute;S CERCANO&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td width="25%"></td>
				<td>Ingrese el punto sobre el cual desea realizar an&aacute;lisis de vecino mas cercano: &nbsp;</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>					
					<!-- Ubicación del panel de mapa -->
					<div id="map" style='width: 100%; height: 400px; border: 0px;'></div>
					<input type="hidden" name="coordenadasRAC" value="" id="coordenadasRAC"/>					
					<hr size=1/>
					<input type="button" name="boton" value="Dibujar Poligono" onclick="toggleControl()">
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					Radio de Influencia (Mts): <input type="text" name="txtRadio" value="10000">
				</td>
			</tr>							
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="button" value="Generar Reporte de Superposici&oacute;n" onclick="window.open('', 'pop', 'width=600,height=500, scrollbars=yes, resizable=yes'); document.forms[0].submit();return false;">
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
<?php

?>
