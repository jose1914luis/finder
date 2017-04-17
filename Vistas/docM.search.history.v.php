<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script language="javascript" type="text/javascript" src="Utilidades/jquery.min.js"></script>
		<script>
			function obtenerAncho() {return ($(document).width()-420);}				
			function viewFolders() {document.frm01.submit();}
			function getHistoryFolder(placa) {if(placa != "")$("#historyFolder").load('Vistas/viewHistoryDocument.v.php?placa='+placa);	}		
		</script>
		<title>:: SIGMIN :: KEEPER</title>
	</head>
	<body>
		<form name="frm01" method="post">
		<table align="center" border="0" >
			<tr>
				<td colspan="2" align="center">
					<?php include("Vistas/document.header.v.php"); ?>
				</td>
			</tr>
			<tr>
				<!-- Area asignada para la selección de históricos -->
				<td width="260" valign="top"> 
					<?php include("Vistas/document.menu.v.php"); ?>					
				</td>
				<script>document.write("<td valign='top' width='"+obtenerAncho()+"'>");</script>				
				<!-- Inicio Contenido de Administración de empresas -->					
					<table align='center' border='0' width='95%' cellspacing='5'>
						<tr>
							<td colspan="5" > 

								<table align="center" width="100%">
										<tr>
											<td bgcolor='#000048' align='center' colspan="2"><b><font color='white'>CONSULTAR&nbsp;&nbsp;&nbsp;HISTORIAL DE EXPEDIENTE</font></b></td>
										</tr>
										<tr>
											<td colspan="2"><hr size="0"></td>
										</tr>												

														<tr>
															<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selecci&oacute;n de la Empresa:</td>
															<td>
															<?php 
																if(!empty($listaEmpresas)) {										
															?>
																<select name="selEmpresa" id="selEmpresa" onchange="viewFolders()">
																<option value="0" selected="selected">Seleccione la Empresa
																<?php
																	$selected = "";
																	foreach($listaEmpresas as $cadaEmpresa) { 		
																		if($_SESSION["idEmpresa"]==$cadaEmpresa["id"])  $selected = "selected='selected'";
																		echo "<option value='".$cadaEmpresa["id"]."' $selected>".strtoupper(utf8_decode($cadaEmpresa["nombre"]))."</option>\n";
																		$selected = "";
																	}													
																?>	                        
																</select>
															<?php } else { 	?>
																<center><h2>No Existen Empresas en el Sistema. Primero debe generar Una Empresa en Keeper</h2></center>
															<?php 
																}  		
															?>		
															</td>
														</tr>
														<tr>
															<td colspan="2"><hr size="0"></td>
														</tr>
								<?PHP 
									if(!empty($tablaSol)) {
								?>	
									<tr>
										<td align="center" colspan="2">
											<p>&nbsp;
											<?=$tablaSol; ?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<!-- Lista de historial de la placa que sea seleccionada-->					
											<div id="historyFolder">&nbsp;</div>
										</td>
								<?php
									} else {
								?>
									<tr>
										<td align="center" colspan="2">
											<p>&nbsp;
											<?=@$sinRegistros; ?>
										</td>
									</tr>
								<?php
									}
								?>
								</table>


							</td>
						</tr>
					</table>
				<!-- Fin del Area asignada para la selección de históricos -->					
				</td>
			</tr>
		</table>
		</form>
	</body>
</html>






