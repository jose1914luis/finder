<html>
	<head>
	</head>	
	<body>
		<p>
		<form method="POST">
		<table align=center width="100%">
			<tr>
				<td colspan=2 align="center">
					<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color='black'>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;PERFIL&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nombre del Grupo:</td>
				<td><input type="text" name="txtNombreGrupo" size=45></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>				
			<tr>
				<td colspan=2 bgcolor="#EEEEEE" align="center">
					<b><font color=black>Selecci&oacute;n de Usuarios y Perfiles</font></b>
				</td>
			</tr>			

			<tr>
				<td align="center">
					Selecci&oacute;n de Perfiles:<br>
					<select name=selPerfil size=10>
					<?php
						foreach($listaPerfiles as $cadaPerfil)
							echo "<option name=\"selPerfil\" value={$cadaPerfil["id"]}>".$cadaPerfil["perfil"]."</option>";
					?>				
					</select>					
				</td>			
				<td align="center">
					Selecci&oacute;n de Usuarios:<br>
					<select name=selUsuario[] size=10 multiple>
					<?php
						foreach($listaUsuarios as $cadaUsuario)
							echo "<option value={$cadaUsuario["id_usuario"]}>".utf8_decode($cadaUsuario["nombre_usuario"])."</option>";
					?>
					</select>										
				</td>				
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="submit" value="Crear Grupo">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" value="Borrar Selecci&oacute;n">
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