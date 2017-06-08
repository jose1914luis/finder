<?php
	@session_start();
	
	require_once("../Acceso/Config.php"); // Definición de las variables globales	
	require_once("../Modelos/IndexacionesQueries.php");
	require_once("../Modelos/DocumentManagement.php");	
	
	// Definición de colores de conformidad con el estado del documento
	$documentStatus = array (
		"Sin Requerimiento" => "#FFFFFF",
		"Pendiente..." => "FFFFBC", //"#FFFF91",
		"Expirado" => "#FC5865", 
		"Completado fuera de tiempo" => "#FFF0E6",
		"Completado a tiempo" => "EEFFEE",//"#EBFEE2"
	);
	
	if (isset($_SESSION["idEmpresa"]) && $_SESSION["idEmpresa"] != "" && @$_GET["placa"] != "") {	
		$folders 			= new DocumentManagement();
		$listadoRegistros 	= $folders->selectHistoryByPlacaEmpresa($_GET["placa"], $_SESSION["idEmpresa"]);
		
		$titulo				= "SIGMIN -  Cronolog&iacute;a del Expediente {$_GET["placa"]}";	

?>
			<table border='1' align='left' cellpadding='5' cellspacing='0'>
				<tr bgcolor="#06526f">
					<td colspan="11" align="center">
						<b><font color='white'>::&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $titulo; ?>&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
					</td>
				</tr>			
				<tr bgcolor="#dfdfdf">
					<td align="center"><b>Expediente</b></td>
					<td align="center"><b>Tipo Documento        </b></td>
					<td align="center"><b>Referencia       </b></td>
					<td align="center"><b>Fecha Radicado   </b></td>					
					<td align="center"><b>Fecha Requerimiento    </b></td>
					<td align="center"><b>Fecha Cumplimiento </b></td>
					<td align="center"><b>Fecha Vencimiento </b></td>
					<td align="center"><b>Documento Resuelve </b></td>
					<td align="center"><b>Referencia Resuelve</b></td>
					<td align="center"><b>Estado          </b></td>
					<td align="center"><b>Tiempo Restante  </b></td>
				</tr>
<?php

	if(!empty($listadoRegistros)) {
	foreach($listadoRegistros as $cadaReg) {
		$color = (!empty($cadaReg["estado_requerimiento"])) ? " bgcolor='{$documentStatus[$cadaReg["estado_requerimiento"]]}' " : "";

?>				
				<tr <?php echo $color ?>>

					<td align="center"><b><a href="javascript:" style="text-decoration:none" onclick="window.open('management.expediente.report.c.php?placa=<?php echo $cadaReg["placa"] ?>&idfrm=<?php echo $cadaReg["idfrm"] ?>', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');"><?php echo $cadaReg["placa"] ?></a></b></td>				
					<td><?php echo utf8_decode($cadaReg["documento"]) ?>&nbsp;</td>
					<td><?php echo utf8_decode($cadaReg["referencia_documento"]) ?>&nbsp;</td>
					<td><?php echo $cadaReg["fecha_documento"] ?>&nbsp;</td>
					<td><?php echo $cadaReg["fecha_inicio_termino"] ?>&nbsp;</td>
					<td><?php echo $cadaReg["fecha_cumple_termino"] ?>&nbsp;</td>
					<td><?php echo $cadaReg["fecha_vence_termino"] ?>&nbsp;</td>
					<td><?php echo utf8_decode($cadaReg["documento_resuelve"]) ?>&nbsp;</td>
					<td><?php echo utf8_decode($cadaReg["referencia_resuelve"]) ?>&nbsp;</td>
					<td><b><?php echo utf8_decode($cadaReg["estado_requerimiento"]) ?>&nbsp;</b></td>
					<td><?php echo $cadaReg["tiempo_vencimiento"] ?>&nbsp;</td>
				</tr>
<?php
	} } else {
?>
			<td align="center" colspan="11"><b>No existen documentos electr&oacute;nicos asociados</b></td>
<?php		
	}
?>				
			</table>
<?php			
			
	} else
		echo "<hr size=1><h3><strong>$titulo: </strong>No existen registros asociados</h3>";
			
?>