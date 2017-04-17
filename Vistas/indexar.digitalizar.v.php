	<script>
	function selFormulario(idPlantilla)	{
			idEmpresa = document.frm01.selEmpresa.options[document.frm01.selEmpresa.selectedIndex].value;
			if(idEmpresa > 0) $("#template").load('viewFormularios.php?idPlantilla='+idPlantilla+"&idEmpresa="+idEmpresa);
			else alert("Debe seleccionar una Empresa");
		};		
	</script>

	<form name="frm01" id="frm01" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="operacionForm" value="indexar.digitalizar"/>
		<table border="0" align="center" width="100%">
			<tr>
				<td bgcolor='#000048' align='center'><b><font color='white'>INDEXAR&nbsp;&nbsp;&nbsp;DOCUMENTO</font></b></td>
			<tr>
				<td><hr size="0"></td>
			</tr>				
			</tr>
			<tr>
				<td align="left">
					<?php						
						if(!empty($listaEmpresas)) { 
					?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selecci&oacute;n de la Empresa:<br>	
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="selEmpresa" id="selEmpresa">
						<option value="0" selected="selected">Seleccione la Empresa
							<?php
								$selected = "";
								foreach($listaEmpresas as $cadaEmpresa) { 		
									if($idEmpresa==$cadaEmpresa["id"])  $selected = "selected='selected'";
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
					<hr size=1/>
				</td>
			</tr>			
			<tr>
				<td align="left">
					<?php if(!empty($listaPlantillas) && !empty($listaEmpresas)) { ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selecci&oacute;n de la plantilla respectiva:<br>	
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="selPlantilla" id="selPlantilla" onchange="selFormulario(this.value)" >
						<option value="0" selected="selected">Seleccione la plantilla
						<?php
							foreach($listaPlantillas as $cadaPlantilla) 
								echo "<option value='".$cadaPlantilla["id"]."' title='".utf8_decode($cadaPlantilla["detalle"])."'>".strtoupper(utf8_decode($cadaPlantilla["nombre"]))."</option>\n";
													
						?>	                        
					</select>				
					<?php } else { 	?>
						<center><h2>No Existen Plantillas en el Sistema. Primero debe generar plantillas en Keeper</h2></center>
					<?php }  		?>	
					<hr size=1/>
				</td>
			</tr>			
			<tr>
				<td width="100%" align="left" valign="top">
			  
					<div id="template">							
						<center><i><b>Debe seleccionar una plantilla de la selecci&oacute;n</b></i></center>
						<hr size="0">
					</div>	
				</td>
			  </tr>
		</table>
	</form>


