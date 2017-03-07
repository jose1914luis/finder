<?php
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ExpedientesSGM.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 
	require_once("/home/sigmin/public_html_services/Modelos/ServiciosSigmin.php");
	require_once("Modelos/FiltrosLiberaciones.php");
	
	$notificar 		= new ExpedientesSGM();	
	$generaURL		= new ControlPopups();
	$filtro 		= new FiltrosLiberaciones();
	$cadenaFiltro	= "";
	
	// Variables filtro para las archivadas:
	if(!empty($_POST)) {	
		$minerales 		= (@$_POST["txtMinerales"]!="") 	? strtoupper($_POST["txtMinerales"]) : "" ;
		$personas  		= (@$_POST["txtPersonas"]!="") 		? strtoupper($_POST["txtPersonas"]) : "" ;
		$municipios 	= (@$_POST["txtMunicipios"]!="") 	? strtoupper($_POST["txtMunicipios"]) : "" ;
		$modalidad 		= (@$_POST["txtModalidad"]!="") 	? strtoupper($_POST["txtModalidad"]) : "" ;		
	} else {
		$filtroArr		= json_decode($filtro->selectFiltroLiberacionByUsuario( $_SESSION['id_usuario']),true);
		
		$minerales 		= strtoupper($filtroArr["minerales"]);
		$personas  		= strtoupper($filtroArr["personas"]);
		$municipios 	= strtoupper($filtroArr["municipios"]);
		$modalidad 		= strtoupper($filtroArr["modalidad"]);
	}

	// para conservar el valor del filtro en el formulario
	$cadenaFiltro	= "
		document.forms['liberaciones'].txtMinerales.value = '$minerales';
		document.forms['liberaciones'].txtMunicipios.value = '$municipios';
		document.forms['liberaciones'].txtPersonas.value = '$personas';
		document.forms['liberaciones'].txtModalidad.value = '$modalidad';
	";	
	
	$listaNotificar	= $notificar->listaAlertasExpedientesArchivados($minerales, $personas, $municipios, $modalidad);
	
	// mensaje	
	$mensaje = "
				<table border='0' align='center' width='95%' class='tableFonts'>
					<tr>
						<td colspan='2'>&nbsp;</td>
					</tr>
					<tr>
						<td colspan='2' class='titleResults' align='center'>Resultados</td>
					</tr>
					<tr>
						<td colspan='2'>&nbsp;</td>
					</tr>
				";
	
	// Se genera la lista de todas las placas pendientes de archivo
	
	if(!empty($listaNotificar))
		foreach($listaNotificar as $cadaNotificacion) {
			//$codAcceso = $generaURL->setControlPopup($cadaNotificacion["placa"], $cadaNotificacion["tipo_expediente"]);
			//$URL_Acceso = "http://www.sigmin.co/services/reporteAreas.php?cod_acceso=$codAcceso";
			
			$URL_Acceso = "?mnu=expedientes_placa&placa={$cadaNotificacion["placa"]}&clasificacion={$cadaNotificacion["tipo_expediente"]}";

			//$listaPlacas	.= $cadaNotificacion["placa"].", ";
			$mensaje 		.= "	
					<tr>
						<td width='35%'><b>Placa Liberada:</b></td>
						<td>{$cadaNotificacion["placa"]}</td>
					</tr>
					<tr>
						<td><b>Tipo de Expediente:</b></td>
						<td>{$cadaNotificacion["tipo_expediente"]}</td>
					</tr>	
					<tr>
						<td><b>Modalidad del Expediente:</b></td>
						<td>{$cadaNotificacion["modalidad"]}</td>
					</tr>				
					<tr>
						<td><b>Fecha de Ejecutoria:</b></td>
						<td>{$cadaNotificacion["fecha_notificacion"]}</td>
					</tr>				
					<tr>
						<td><b>Vigencia del &Aacute;rea hasta:</b></td>
						<td>{$cadaNotificacion["fecha_vencimiento"]}</td>
					</tr>
					<tr>
						<td><b>Minerales:</b></td>
						<td>".utf8_decode($cadaNotificacion["minerales"])."</td>
					</tr>				
					<tr>
						<td><b>Titulares:</b></td>
						<td>".utf8_decode($cadaNotificacion["personas"])."</td>
					</tr>				
					<tr>
						<td><b>Municipios:</b></td>
						<td>".utf8_decode($cadaNotificacion["municipios"])."</td>
					</tr>
					<tr><td colspan='2'><hr size='1'></td></tr>
					<tr>
						<td colspan='2'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b><a href='$URL_Acceso' title='Ver poligono asociado al expediente {$cadaNotificacion["placa"]}'>Poligono Asociado</a></b>
						</td>
					</tr>
					<tr>
						<td colspan='2'>&nbsp;</td>
					</tr>
					<tr>
						<td colspan='2'><hr size='1'></td>
					</tr>					
			";
		}	
	else
		$mensaje .= "<tr>
						<td colspan='2' align='center'><h2>No hay expedientes archivados para reportar</h2></td>
					</tr>";

		
	$mensaje .= "</table>";
	
	// funcion de envío de email
	//$res = $notificar->enviar_email($server_email, $para, $asuntoMsg, $mensaje);
?>

<script>
	function guardarFiltro() { 	
		$.post('viewReleaseSaveFilters.php', {minerales: document.forms["liberaciones"].txtMinerales.value.trim(), personas: document.forms["liberaciones"].txtPersonas.value.trim(), municipios: document.forms["liberaciones"].txtMunicipios.value.trim(), modalidad: document.forms["liberaciones"].txtModalidad.value.trim()}, function(resp) {
				if(resp!="")  {					
					eval(resp);
				} else
					alert("No hay retorno de información");
			});	
		document.forms["liberaciones"].submit();			
	}
	
	function limpiarFiltro() {
		document.forms["liberaciones"].reset();
		guardarFiltro();
	}	
</script>



	<table border='0' align='center' width='95%' class='tableFonts'>
		<tr>
			<td colspan='2' class="titleSite" align='center'><b>Notificaciones por Liberaci&oacute;n de &Aacute;rea</b></td>
		</tr>
		<tr>
			<td colspan='2' align='center'>&nbsp;</td>
		</tr>		
		<tr>
			<td>
				<div>
					<form name="liberaciones" action="?mnu=liberaciones" method="post">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="txtMinerales" id="txtMinerales" placeholder="  Mineral...">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="txtPersonas" id="txtPersonas" placeholder="  Persona...">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="txtMunicipios" id="txtMunicipios" placeholder="Departamento/Municipio...">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="txtModalidad" id="txtModalidad"  placeholder="  Modalidad...">
						<div>&nbsp;</div>
						<hr size="0">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Filtrar">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Limpiar" onclick="limpiarFiltro()">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Guardar Filtro" onclick="guardarFiltro()">
						<hr size="0">
					</form>
				</div>
			</td>		
		</tr>
	</table>

<div>
	<?=utf8_decode($mensaje); ?>
</div>

	<script>
<?php
	if(@$cadenaFiltro != "")
		echo $cadenaFiltro;
?>	
	</script>		