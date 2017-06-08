<html>
	<head>
	</head>	
	<body>
		<p>
		<form method="post">
		<table align="center" width="100%">
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
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nombre del Perfil:</td>
				<td><input type="text" name="txtNombrePerfil" size=45></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>				
			<tr>
				<td colspan=2 bgcolor="#AFAFAF" align="center">
					<b><font color=black>ASIGNACI&Oacute;N DE ROLES DE ACCESO</font></b>
				</td>
			</tr>			

			<tr>
				<td colspan=2 align="center">
				<table border=0 width="95%">
					<?php
						$numeroAcciones=1;
						$listadoRoles = $acciones->selectListAcciones();
						$moduloActual = "";
						
						foreach($listadoRoles as $cadaRol) {
							if($moduloActual!=$cadaRol["modulo"]) {
								$moduloActual = $cadaRol["modulo"];
								$numeroAcciones=1;								
								if($moduloActual!="")	echo "</tr>";
								echo "<tr><td align=\"center\" bgcolor=\"DDDDDD\" colspan=\"5\"><b>".strtoupper(utf8_decode($moduloActual))."</b></td>";
							}
							
							if($numeroAcciones%5==1) 	echo "<tr align=center>";
							echo "<td width=\"20%\" align=\"center\"  valign=\"top\"><hr size=1>".utf8_decode($cadaRol["accion"])."<br><input type=\"checkbox\" name=rol[{$cadaRol["id_accion"]}] value=\"{$cadaRol["id_accion"]}\"></td>";

							$numeroAcciones++;
						} // fin de foreach
					?>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="submit" value="Crear Perfil">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="Borrar Selecci&oacute;n">
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