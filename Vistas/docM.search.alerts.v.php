<?php
	@session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/IndexacionesQueries.php");
	require_once("Modelos/DocumentManagement.php");	
	
	// Definición de colores de conformidad con el estado del documento
	$documentStatus = array (
		"Pending..." => "#FFFFB7",
		"Expired" => "#FFB7B7", 
		"Complete out of time" => "#FFF0E6",
		"Complete in time" => "#EBFEE2"
	);
	
	if (isset($_SESSION["idEmpresa"]) && $_SESSION["idEmpresa"] != "") {	
		$folders 			= new DocumentManagement();
		$listadoRegistros 	= $folders->selectAllAlerts($_SESSION["idEmpresa"]);
		
		$titulo				= "Document Management -  Lista de Alertas";	

?>
<table align="center" width="100%">
				<tbody><tr>
					<td align="center">
						<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color="black">::&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENT&nbsp;&nbsp;&nbsp;MANAGEMENT&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;ALERTAS&nbsp;&nbsp;&nbsp;&nbsp;PENDIENTES&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
					</td>
				</tr>			
				<tr>
					<td align="center">
						&nbsp;<p>
						<table border='1' align='left' cellpadding='5' cellspacing='0'>
							<tr bgcolor="#06526f">
								<td colspan="13" align="center">
									<b><font color='white'>::&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $titulo; ?>&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
								</td>
							</tr>			
							<tr bgcolor="#dfdfdf">
								<td align="center"><b>Placa								</b></td>
								<td align="center"><b>Documento							</b></td>
								<td align="center"><b>Referencia						</b></td>
								<td align="center"><b>Genera Requerimiento 				</b></td>
								<td align="center"><b>Recibe Requerimiento				</b></td>					
								<td align="center"><b>Fecha Documento    				</b></td>
								<td align="center"><b>Inicio T&eacute;rminos 			</b></td>
								<td align="center"><b>Fecha Entrega				     	</b></td>
								<td align="center"><b>Vence T&eacute;rminos  			</b></td>
								<td align="center"><b>Documento Resuelve				</b></td>					
								<td align="center"><b>Referencia Resuelve    			</b></td>
								<td align="center"><b>Estado Requerimiento 				</b></td>
								<td align="center"><b>Tiempo Vencimiento		     	</b></td>
							</tr>
<?php
	if(!empty($listadoRegistros))
	foreach($listadoRegistros as $cadaReg) {
		$color = (!empty($cadaReg["estado_requerimiento"])) ? " bgcolor='{$documentStatus[$cadaReg["estado_requerimiento"]]}' " : "";

?>				
							<tr <?php echo $color ?>>

								<td align="center"><b><a href="javascript:" style="text-decoration:none" onclick="window.open('management.expediente.report.c.php?placa=<?php echo $cadaReg["placa"] ?>&idfrm=<?php echo $cadaReg["idfrm"] ?>', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');"><?php echo $cadaReg["placa"] ?></a></b></td>
								<td><?php echo utf8_decode($cadaReg["documento"]) ?>&nbsp;</td>
								<td><?php echo utf8_decode($cadaReg["referencia_documento"]) ?>&nbsp;</td>
								<td><?php echo utf8_decode($cadaReg["genera_documento"]) ?>&nbsp;</td>
								<td><?php echo utf8_decode($cadaReg["recibe_requerimiento"]) ?>&nbsp;</td>
								<td><?php echo $cadaReg["fecha_documento"] ?>&nbsp;</td>
								<td><?php echo $cadaReg["fecha_inicio_termino"] ?>&nbsp;</td>
								<td><?php echo $cadaReg["fecha_cumple_termino"] ?>&nbsp;</td>
								<td><?php echo $cadaReg["fecha_vence_termino"] ?>&nbsp;</td>
								<td><?php echo $cadaReg["documento_resuelve"] ?>&nbsp;</td>
								<td><?php echo $cadaReg["referencia_resuelve"] ?>&nbsp;</td>
								<td><b><?php echo $cadaReg["estado_requerimiento"] ?>&nbsp;</b></td>
								<td><?php echo $cadaReg["tiempo_vencimiento"] ?>&nbsp;</td>							
							</tr>
<?php
	}
?>				
						</table>
<?php			
			
	} 
			
?>

						<p>&nbsp;
					</td>
				</tr>
</tbody></table>