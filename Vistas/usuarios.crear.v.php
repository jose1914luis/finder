<html>
	<head>
	</head>	
		<body>
			<p>
			<form name "frmUsuario" method="POST">
			<table align=center width="50%">
				<tr>
					<td colspan=2 bgcolor="#000077" align="center">
						<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;USUARIO&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
					</td>
				</tr>			
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
					<td>N&uacute;mero de Documento:</td>
					<td><input type="text" name="usr_nro_documento"></td>
				</tr>
				<tr>
					<td>Nombre del Empleado:</td>
					<td><input type="text" name="usr_nombre" size=45></td>
				</tr>
				<tr>
					<td>Correo Electr&oacute;nico:</td>
					<td><input type="text" name="usr_email" size=45></td>
				</tr>
				<tr>
					<td>Tel&eacute;fono Oficina:</td>
					<td><input type="text" name="usr_tel_oficina"></td>
				</tr>
				<tr>
					<td>N&uacute;mero de Celular:</td>
					<td><input type="text" name="usr_celular" size=35></td>
				</tr>
				<tr>
					<td>Generaci&oacute;n de Login del Empleado:</td>
					<td><input type="text" name="usr_login"></td>
				</tr>				
				<tr>
					<td>Contrase&ntilde;a:</td>
					<td><input type="password" name="usr_contrasenia"></td>
				</tr>
				<tr>
					<td>Repetir Contrase&ntilde;a:</td>
					<td><input type="password" name="usr_recontrasenia"></td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
						<input type="submit" value="Crear Empleado">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="Eliminar Datos">
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