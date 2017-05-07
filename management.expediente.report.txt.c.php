<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definici�n de las variables globales	
	require_once("Modelos/DocumentManagement.php");	
//	require_once("Modelos/SeguimientosUsuarios.php");	
//	require_once("Modelos/Usuarios.php");
	
	// validaci�n de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
	$msgAcceso = "";
	
	if (isset($_POST["idDocumento"]) && $_POST["idDocumento"]!="") {
		$dManagement	= new DocumentManagement();
		$documentos 	= $dManagement->selectTextDocumentByFormDocument($_POST["idDocumento"]);
		$docPlantilla	= $dManagement->selectDocPlantillaByFormDocument($_POST["idDocumento"]);
		$requerimientos = $dManagement->selectDocRequierenByFormDocument($_POST["idDocumento"]);
		$resueltos		= $dManagement->selectDocResuelvenByFormDocument($_POST["idDocumento"]);
		
		echo "
			<html>
			<head>
				<style type=\"text\/css\">
				<!--
				.Estilo1 {
				font-weight: bold;
				font-size: 18px;
				}
				-->
				</style>			
			</head>
			<body>
				<table border='0' align='center' width='90%'>
				<tr bgcolor='fff3f3'>
					<th align='center' colspan='2' class='Estilo1'>".strtoupper($documentos[0]["formulario"])." - EXPEDIENTE ".strtoupper($docPlantilla["placa"])." -</th>
				</tr>
				<tr bgcolor='eeeeee'>
					<th align='center'>INDICE</th>
					<th align='center'>INFORMACI&Oacute;N</th>
				</tr>
		";	
	
		echo "
			<tr><td align='left'>GENERA DOCUMENTO</td><td align='justify'>".utf8_decode($docPlantilla["genera_documento"])."</td></tr>
			<tr><td align='left'>PLACA</td><td align='justify'>".utf8_decode($docPlantilla["placa"])."</td></tr>						
			<tr><td align='left'>N&Uacute;MERO RADICADO</td><td align='justify'><b>".utf8_decode($docPlantilla["numero_radicado"])."</b></td></tr>			
			<tr><td align='left'>FECHA RADICADO</td><td align='justify'>".utf8_decode($docPlantilla["fecha_radicado"])."</td></tr>			
			<tr><td align='left'>REFERENCIA DOCUMENTO</td><td align='justify'>".utf8_decode($docPlantilla["referencia"])."</td></tr>			
			<tr><td align='left'>N&Uacute;MERO DE FOLIOS</td><td align='justify'>".utf8_decode($docPlantilla["numero_folios"])."</td></tr>			
		";		

		if(!empty($documentos))
			foreach($documentos as $cadaDocumento) {
				echo "
					<tr>
						<td align='left'>".utf8_decode($cadaDocumento["indice"])."</td>
						<td align='justify'>".utf8_decode($cadaDocumento["dato"])."</td>
					</tr>			
				";		
			}
		
		$nroReq = 1;	
		if(!empty($requerimientos))
			foreach($requerimientos as $cadaReq) {
				$resuelto = ($cadaReq["resuelto_por"]!="NO RESUELTO") ? "<a href='http://www.sigmin.co/research/Keeper/management.expediente.report.c.php?placa={$docPlantilla["placa"]}&idfrm={$cadaReq["id_doc_resuelve"]}' target='_top'>{$cadaReq["resuelto_por"]}</a>" : $cadaReq["resuelto_por"];
				
				echo "
					<tr>
						<td align='center' colspan='2'><HR></td>
					</tr>			
					<tr bgcolor='dddddd'>
						<td align='center' colspan='2' class='Estilo1'><b>Requerimiento Nro. $nroReq</b></td>
					</tr>
					<tr>
						<td align='left'>TIPO DE REQUERIMIENTO</td>
						<td align='justify'>".utf8_decode($cadaReq["tipo_requerimiento"])."</td>
					</tr>			
					<tr>
						<td align='left'>FECHA DEL REQUERIMIENTO</td>
						<td align='justify'>{$cadaReq["fecha_requerimiento"]}</td>
					</tr>			
					<tr>
						<td align='left'>FECHA DE VENCIMIENTO</td>
						<td align='justify'>{$cadaReq["fecha_vencimiento"]}</td>
					</tr>						
					<tr>
						<td align='left'>REQUERIMIENTO ASIGNADO A</td>
						<td align='justify'>".utf8_decode($cadaReq["requerido_a"])."</td>
					</tr>			
					<tr>
						<td align='left'>DETALLE DEL REQUERIMIENTO</td>
						<td align='justify'>".utf8_decode($cadaReq["descripcion"])."</td>
					</tr>
					<tr>
						<td align='left'>RESUELTO POR</td>
						<td align='justify'><b>".utf8_decode($resuelto)."</b></td>
					</tr>
					<tr>
						<td align='left'>ESTADO SATISFACCION</td>
						<td align='justify'>{$cadaReq["estado_satisfaccion"]}</td>
					</tr>			
				";		
				$nroReq++;
			}		

		$nroRes = 1;	
		if(!empty($resueltos))
			foreach($resueltos as $cadaRes) {
				echo "
				<tr>
					<td align='center' colspan='2'><HR></td>
				</tr>			
				<tr bgcolor='dddddd'>
					<td align='center' colspan='2' class='Estilo1'><b>Cumplimiento Nro. $nroRes</b></td>
				</tr>
				<tr>
					<td align='left'>RADICADO RESUELTO</td>
					<td align='justify'><b><a href='http://www.sigmin.co/research/Keeper/management.expediente.report.c.php?placa={$docPlantilla["placa"]}&idfrm={$cadaRes["id_doc_requiere"]}' target='_top'>{$cadaRes["radicado_requiere"]}</a></b></td>
				</tr>			
				<tr>
					<td align='left'>FECHA DE CUMPLIMIENTO</td>
					<td align='justify'>{$cadaRes["fecha_cumplimiento"]}</td>
				</tr>			
				<tr>
					<td align='left'>DETALLE DEL CUMPLIMIENTO</td>
					<td align='justify'>".utf8_decode($cadaRes["detalle_cumplimiento"])."</td>
				</tr>
				<tr>
					<td align='left'>ESTADO SATISFACCI&Oacute;N</td>
					<td align='justify'>".utf8_decode($cadaRes["estado_satisfaccion"])."</td>
				</tr>
				";		
				$nroRes++;
			}		
		

			
		echo "	
				<tr>
					<td align='center' colspan='2'><HR></td>
				</tr>			
			</table>";
				
		echo "</body></html>";
				
	} 
		//header("Location: DocumentosElectronicos/r2d2DocumentManagement.pdf"); 
	
?>	

