<html>
	<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>
		// Definición de variables locales y globales de la página
		function seleccionarMunicipio(idDepto)	{
			 $("#emp_id_ciudad").load('viewMunicipios.php?idDepto='+idDepto);
		};		
	</script>	
	</head>
		<body>
			<p>
			<form name "frmEmpresa" method="POST">
			<table align=center width="50%">
				<tr>
					<td colspan=2 bgcolor="#000077" align="center">
						<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;EMPRESA&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
					</td>
				</tr>			
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td>NIT de la Empresa:</td>
					<td><input type="text" name="emp_nit"></td>
				</tr>
				<tr>
					<td>Nombre de la Empresa:</td>
					<td><input type="text" name="emp_nombre" size=45></td>
				</tr>
				<tr>
					<td>Correo Electr&oacute;nico:</td>
					<td><input type="text" name="emp_email"></td>
				</tr>
				<tr>
					<td>Fax:</td>
					<td><input type="text" name="emp_fax"></td>
				</tr>
				<tr>
					<td>Apartado A&eacute;reo:</td>
					<td><input type="text" name="emp_apartado_aereo"></td>
				</tr>
				<tr>
					<td>Tel&eacute;fono:</td>
					<td><input type="text" name="emp_telefono"></td>
				</tr>
				<tr>
					<td>Direcci&oacute;n:</td>
					<td><input type="text" name="emp_direccion" size=35></td>
				</tr>
				<tr>
					<td>Departamento:</td>
					<td>
						<select id=emp_id_departamento name=emp_id_departamento onChange="seleccionarMunicipio(this.value)">
							<option value=0>Seleccione Departamento</option>
							<?php
								foreach($deptos->selectAll() as $cadaDepto) {
							?>
								<option value=<?php echo $cadaDepto["id"] ?>> <?php echo utf8_decode($cadaDepto["nombre"]) ?> </option>

							<?php
								}
							?>
						</select>												
					</td>
				</tr>
				<tr>
					<td>Ciudad:</td>
					<td>
						<select id=emp_id_ciudad name=emp_id_ciudad >
							<option value=0>Seleccione Municipio</option>
						</select>																
					</td>
				</tr>	
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
						<input type="submit" value="Crear Empresa">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="Eliminar Datos">
						<hr size=1/>
					</td>
				</tr>				
				
			</table>
			</form>
			<?php
				echo $msgError;
			?>
		</body>
</html>
