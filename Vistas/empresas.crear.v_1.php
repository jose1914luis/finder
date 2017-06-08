<html>
	<head>
	<script type="text/javascript" src="Utilidades/jquery.min.js"></script>
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
			<input type="hidden" name="operacionForm" value="empresas.crear">
			<table align="center" width="100%">
				<tr>
					<td colspan=2 align="center">
						<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color='black'>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;EMPRESA&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
					</td>
				</tr>	
				<tr>
					<td colspan="2" bgcolor="#ededed">
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos Generales</b>						
					</td>
				</tr>				
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td>NIT:</td>
					<td><input type="text" name="emp_nit"></td>
				</tr>
				<tr>
					<td>Nombre:</td>
					<td><input type="text" name="emp_nombre" size=45></td>
				</tr>
				<tr>
					<td>Correo Electr&oacute;nico:</td>
					<td><input type="text" name="emp_email" size="40"></td>
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
					<td>Fecha Inicio Contrato:</td>
					<td><input type="text" name="emp_fecha_inicio_contrato" size="15" placeholder="dd/mm/yyyy"></td>
				</tr>
				<tr>
					<td>Duracion Contrato:</td>
					<td><input type="text" name="emp_duracion_contrato_meses" size="10"> Meses</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="#ededed">
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Representante Legal</b>						
					</td>
				</tr>			
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td>N&uacute;mero Documento:</td>
					<td><input type="text" name="rl_numero_documento"></td>
				</tr>
				<tr>
					<td>Tipo Documento:</td>
					<td>
						<select id="rl_tipo_identificacion" name="rl_tipo_identificacion">
							<option value=0>Seleccione Tipo de Documento</option>
							<?php
								foreach($tiposIdentificacion->selectTiposNatural() as $cadaTipoIdentifica) {
							?>
								<option value=<?=$cadaTipoIdentifica["id"] ?>> <?=$cadaTipoIdentifica["nombre"] ?> </option>								
							<?php
								}
							?>
						</select>												
					</td>
				</tr>				
				<tr>
					<td>Nombre:</td>
					<td><input type="text" name="rl_nombre" size=45></td>
				</tr>
				<tr>
					<td>Correo Electr&oacute;nico:</td>
					<td><input type="text" name="rl_email" size="40"></td>
				</tr>
				<tr>
					<td>M&oacute;vil:</td>
					<td>
						<input type="text" name="rl_celular">
					</td>
				</tr>				
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
						<input type="submit" value="Crear Empresa">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="Borrar Datos">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="#ededed">
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lista de Empresas</b>						
					</td>
				</tr>				
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table align="left" border="1" width="95%" cellpadding="0" cellspacing="0">
							<tr bgcolor="#ddfefe">
								<td align="center"><b>NIT</b></td>
								<td align="center"><b>Empresa</b></td>
								<td align="center"><b>Inicio Contrato</b></td>
								<td align="center"><b>Vence Contrato</b></td>
								<td align="center"><b>Contrato</b></td>
								<td align="center"><b>Editar</b></td>
							</tr>
					<?php 
						foreach($empresa->selectAll() as $cadaEmpresa) {
					?>
							<tr>
								<td><?= $cadaEmpresa["nit"] ?></td>
								<td><?= $cadaEmpresa["nombre"] ?></td>
								<td><?= $cadaEmpresa["fecha_inicio_contrato"] ?></td>
								<td><?= $cadaEmpresa["fecha_fin_contrato"] ?></td>
								<td align="center"><?= $cadaEmpresa["contrato"] ?></td>
								<td align="center"><a href="javascript:<?= $cadaEmpresa["id"] ?>"><img src="Imagenes/editarEmp.png" border="0"></a></td>
					<?php
						}
					?>
							</tr>
						</table>
					</td>
				</tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>				
			</table>
			</form>
			<?php
				echo $msgError;
			?>
		</body>
</html>
