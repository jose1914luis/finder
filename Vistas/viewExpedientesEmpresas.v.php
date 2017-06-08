<html>
	<head>
		<script language="javascript" type="text/javascript" src="Utilidades/jquery.min.js"></script>
		<script>
			function obtenerAncho() {return ($(document).width()-420);}	
			function viewFolders() {document.frm01.submit();}
			function asociar_expediente(placa) {document.frm01.asociarExpediente.value="SI";document.frm01.submit();}			
		</script>
		<title>:: SIGMIN :: Document Management</title>
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
				<!-- Area asignada para la definición del menu de seleccion -->
				<td width="260" valign="top"> 
					<?php include("Vistas/document.menu.v.php"); ?>					
				</td>
				<script>document.write("<td valign='top' width='"+obtenerAncho()+"'>");</script>				
				<!-- Inicio Contenido de Administración de empresas -->					
				<table align='center' border='0' width='95%' cellspacing='5'>
					<tr>
						<td colspan="5" > 
								<table border="0" align="center" width="100%">
									<tr>
										<td bgcolor='#000048' align='center' colspan="2"><b><font color='white'>CARGAR&nbsp;&nbsp;&nbsp;EXPEDIENTES</font></b></td>
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
									<?php 
										if(!empty($listaEmpresas)) {
									?>
									<tr>
										<td colspan="2"><hr size="0"></td>
									</tr>							
									<tr>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Asociar Expediente:</td>
										<td>	<input type="text" name="codExpediente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input type="button" value="Asociar" onclick="asociar_expediente(this.value)">
												<input type="hidden" name="asociarExpediente" value="NO">
										</td>
									</tr>
									<?php
										}
									?>
									<tr>
										<td colspan="2"><hr size="0"></td>
									</tr>									
								</table>
							
							<?PHP
								$folders 			= new DocumentManagement();
								$listadoRegistros 	= $folders->selectExpedientesByEmpresa($_SESSION["idEmpresa"]);
								
								$titulo				= " Expedientes Registrados ";	
								$clasificacion 		= "";
								
								if(!empty($listadoRegistros))  {		
									$nroRegistros 	= sizeof($listadoRegistros);
									// $cols = ($nroRegistros < 4) ? $nroRegistros  : 4;	
									$cols = 5;
									
									$tablaSol  = "
									<table border='0' align='center' width='85%'>
										<tr bgcolor='#06526F'>
											<td colspan='$cols'>
											<font color='#ffffff'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$titulo."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Folders: $nroRegistros </font>&nbsp;&nbsp;&nbsp;
											</td>					
										</tr>
										<tr>
											<td colspan='$cols'>&nbsp;</td>
										</tr>
									";
									
									for($i=0;$i<$nroRegistros;$i++) {
										
										if(!$i%5) $tablaSol .= "<tr>";
										
										$enlace = "<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa=".$listadoRegistros[$i]["expediente"]."&idfrm=0', 'pop3', 'width=800,height=900, resizable=yes, scrollbars=yes');\"><img src='Imagenes/expedienteDocumentos_dm.gif' border='0' width='78' height='74' title='Reporte del Expediente ".strtoupper($listadoRegistros[$i]["expediente"])."'></a>";
										
										$tablaSol .= "<td align='center'>$enlace <br/> <b>".strtoupper($listadoRegistros[$i]["expediente"])."</b></td>";
										
										if($i%5 == 4 || ($i+1) == $nroRegistros)	$tablaSol .= "</tr>";	
									}	
									$tablaSol .= "
										<tr>
											<td colspan='$cols'>&nbsp;</td>
										</tr>							
										<tr>
											<td colspan='$cols'><hr size='0'></td>
										</tr>				
									</table>
									";			
									
						?>
								<table align="center" width="100%">
									<tr>
										<td colspan=2 align="center">
											<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color='black'>::&nbsp;&nbsp;&nbsp;&nbsp;Empresa <?=strtoupper($empresa->selectNameByID($_SESSION["idEmpresa"]))?>&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
										</td>
									</tr>			
									<tr>
										<td colspan=2 align="center">
											<p>&nbsp;
											<?=$tablaSol; ?>
									</td>
									</tr>
								</table>
							<?php													
								} else
									echo "<center><h3><strong>$titulo: </strong>No existen registros</h3></center><hr size=1>";

								
							?>
						</td>
					</tr>
				</table>
				<!-- Fin Contenido de Administración de empresas -->					
			</td>
		</tr>
	</table>
	</form>
	<?php
		if($msgTransaccion != "")
			echo "<script>alert('$msgTransaccion')</script>";
	?>
	</body>
</html>





