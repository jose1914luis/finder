<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Variables/ArraysDatos.php");
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/ZonasAlarma.php");

	// variables del controlador	
	$msgError 	= "";
	$Id_Empresa = 2;  // pendiente de volver variable global
	$prp 		= new ProspectosBogSGM();
	$zal		= new ZonasAlarma();

	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["listEmails"])&&trim($_POST["listEmails"])!=""&&$_POST["selProspecto"]!="0") {	
		$resultado = $zal->insertAll($_POST, $Id_Empresa);
		if($resultado != "OK")
			$msgError = "<script>alert('Error durante el proceso de creación de Alarma al prospecto {$_POST["selProspecto"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('Alarma generada satisfactoriamente para el prospecto {$_POST["selProspecto"]}')</script>";
	}

	// include("Vistas/proyectos.crear.v.php");	
?>
	
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>Free Zone Alarm :: SIGMIN</title>

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
			
			function cambiarProspecto(campoPlaca) { 
				$.post('viewValidaPlaca.php', { selProspecto: document.forms[0].selProspecto.value}, function(resp) {
						if(resp!="") 
							eval(resp);
						else
							alert("No hay retorno de información");
					});				
			}
			
			function anexarEmail() {
				campo = document.forms[0].txtEmailNotificar.value;
				document.forms[0].txtEmailNotificar.value = "";
				
				if(document.forms[0].listEmails.value.length)
					document.forms[0].listEmails.value += "\n" + campo;
				else	
					document.forms[0].listEmails.value = campo;
			}
			

		</script>
	<style type="text/css">
		caption { 
		  caption-side: top; 
		}	
	</style>
	</head>
	<body onload="init()">
		<p>
		<form method="post">
		<table align=center width="900">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;FREE&nbsp;&nbsp;&nbsp;ZONE&nbsp;&nbsp;&nbsp;ALARM&nbsp;::</font></b>
				<td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				<td>
			</tr>
			<tr>
				<td colspan=2 align="justify">
				En Free Zone Alarm se ingresan los email electr&oacute;nicos ha notificar una vez se presenten &aacute;reas libres derivadas del archivo, cancelaci&oacute;n, renuncia o terminaci&oacute;n de t&iacute;tulos y solicitudes mineras.  El &aacute;rea de influencia para las alarmas se define a partir de la selecci&oacute;n de un predio minero previamente establecido. 
				<td>
			</tr>	
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				<td>
			</tr>			
			<tr>
				<td colspan=2>
					Seleccione el Prospecto Minero: &nbsp;&nbsp;&nbsp;&nbsp;
					<select name="selProspecto" onChange="cambiarProspecto(this);">
						<option value="0">Seleccione un prospecto
						<?php
							foreach($prp->selectProspectoByEmpresa($Id_Empresa) as $cadaProspecto)
								echo "<option value='{$cadaProspecto["placa"]}'>{$cadaProspecto["placa"]}";
						?>
					</select><p>&nbsp;
					<!-- Ubicación del panel de mapa -->
					<div id="map" style='width: 100%; height: 400px; border: 0px;'></div>
					<hr size='1'>
				</td>
			</tr>
			<tr>
				<td class=caption>
						Ingreso de Email a Notificar:<br>
						<input type="text" name="txtEmailNotificar" size=35>&nbsp;
						<input type="button" name="btnAgregarEmail" value=" >> " onClick="anexarEmail()"><p>
				</td>
				<td>
						Emails Registrados:<br>
						<textarea name="listEmails" rows="7" cols="35" readonly></textarea>										
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="submit" value="Crear Alarma de Notificaci&oacute;n">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="Eliminar Datos" onclick="clearFields()">
					<hr size=1/>
				<td>
			</tr>				
		</table>
		</form>
		<?php
			if($msgError)
				echo $msgError;
		?>		
	</body>
</html>
	


