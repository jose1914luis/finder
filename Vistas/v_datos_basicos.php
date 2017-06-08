	<script>
		function enviarForm() {		
			if(!validarDatosUsuario()) return 0;
			document.frmAdminUser.submit();
	    } 
		
		// selecciona mpio dado departamento
		function seleccionarMunicipio(idDepto)	{
			 $("#selMunicipio").load('viewMunicipios.php?idDepto='+idDepto);
		};		
		
		// seleccion de acuerdo al tipo de documento
		function cambioCrazon(idTipoDocumento)	{
			var txtRazon ="";
			if(idTipoDocumento!=5) {
				txtRazon += '<input type="text" name="txtNombre" size="25" placeholder="Nombres" value="<?=$listaForm["nombres"]?>" <?=$readonly?>>&nbsp;&nbsp;';
				txtRazon += '<input type="text" name="txtApellido" size="25" placeholder="Apellidos" value="<?=$listaForm["apellidos"]?>" <?=$readonly?>>';
			} else {
				txtRazon += '<input type="text" name="txtNombre" size="55" placeholder="Razón Social" value="<?=$listaForm["razon_social"]?>" <?=$readonly?>>';
				txtRazon += '<input type="hidden" name="txtApellido" value=""></div>';			
			}
				$("#crazon").html(txtRazon);
		};	
	</script>
	
<form name="frmAdminUser" method="post" action="?mnu=datos_basicos<?php echo $rest = (@$_GET["credits"]==1) ? "&credits=1" : "" ?>">
	<table width="95%" border="0" cellspacing="0" align="center" class="tableFonts">
		<tr>
			<td colspan="2" class="titleSite" align='center'>Datos de Usuario</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>	
		<tr class="results">
			<td class="results">Tipo de Documento:*</td>
			<td class="results">
				<select type="text" id="selTipoDocumento" name="selTipoDocumento" onChange="cambioCrazon(this.value)">
				<?php
					if($readonly!="")
						echo "<option value='{$listaForm["id_tipo_documento"]}' selected>{$listaForm["tipo_documento"]}</option>";
					else {
				?>
						<option value="0">Seleccione Tipo de Documento</option>
				<?php
						foreach($identificacion->selectAll() as $cadaDocumento) {
				?>
						<option value=<?=$cadaDocumento["id"]?>> <?=($cadaDocumento["nombre"])?> </option>
					<?php
						}
					}
				?>					
				</select>				
			</td>
		</tr>
		<tr class="results">
			<td class="results">N&uacute;mero de Documento:*</td>
			<td class="results">
				<input type="text" name="txtDocumento" size="45" value="<?=$listaForm["numero_documento"]?>" readonly />
			</td>
		</tr>
		<tr class="results">
			<td class="results">Nombre/Raz&oacute;n Social:*</td>
			<td class="results">
				<div id="crazon">
					<input type="text" name="txtNombre" size="25" placeholder="Nombres" value="<?=$listaForm["nombres"]?>" <?=$readonly?>>&nbsp;&nbsp;
					<input type="text" name="txtApellido" size="25" placeholder="Apellidos" value="<?=$listaForm["apellidos"]?>" <?=$readonly?>>
				</div>				
			</td>
		</tr>
		<tr class="results">
			<td class="results">Fecha de Nacimiento:*</td>
			<td class="results">
				<input name="txtFechaNacimiento" type="text" size="25" value="<?=$listaForm["fecha_nacimiento"]?>" placeholder="DD/MM/AAAA"/>
			</td>
		</tr>		
		<tr class="results">
			<td class="results">Correo Electr&oacute;nico:*</td>
			<td class="results">
				<input name="buyerEmail" type="text" size="60" value="<?=$listaForm["correo_electronico"]?>" readonly />
			</td>
		</tr>	
		<tr class="results">
			<td class="results">Tel&eacute;fono:</td>
			<td class="results">
				<input type="text" name="txtTelefono" size="25"  value="<?=$listaForm["telefono"]?>">
			</td>
		</tr>		
		<tr class="results">
			<td class="results">N&uacute;mero Celular:</td>
			<td class="results">
				<input type="text" name="txtCelular" size="25" value="<?=$listaForm["celular"]?>">
			</td>
		</tr>
		<tr class="results">
			<td class="results">Departamento:*</td>
			<td class="results">
				<select type="text" id="selDepartamento" name="selDepartamento" onChange="seleccionarMunicipio(this.value)">
				<?php
					if($readonly!="")
						echo "<option value='{$listaForm["id_departamento"]}' selected>{$listaForm["departamento"]}</option>";				
				?>				
					<option value="0">Seleccione Departamento</option>
				
					<?php
						foreach($deptos->selectAll() as $cadaDepto) {
					?>
						<option value=<?=$cadaDepto["id"]?>> <?=($cadaDepto["nombre"])?> </option>
					<?php
						}
					?>					
				</select>				
			</td>
		</tr>	
		<tr class="results">
			<td class="results">Municipio:*</td>
			<td class="results">
				<select type="text" id="selMunicipio" name="selMunicipio">
				<?php
					if($readonly!="")
						echo "<option value='{$listaForm["id_municipio"]}' selected>{$listaForm["municipio"]}</option>";				
				?>							
					<option value="0">Seleccione Municipio</option>
				</select>
			</td>
		</tr>
		
		<tr class="results">
			<td class="results">Dirección*</td>
			<td class="results">
				<input type="text" name="txtDireccion" size="60"  value="<?=$listaForm["direccion"]?>">
			</td>
		</tr>	
		<tr class="results">
			<td colspan="2" align="center" class="results">
				<hr size="0">
				<center><input type="button" name="type" id="cambioClave" title="Aplicar Cambio" value="Guardar Información" onClick="enviarForm()"/></center>
				<hr size="0">
			</td>
		</tr>
	</table>	
</form>	
