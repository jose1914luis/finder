<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Perfiles.php");
	require_once("Modelos/Acciones.php");
	
	$acciones = new Acciones();
	$prf 	= new Perfiles();

	// variables del controlador	
	$msgError = "";
	$Id_Empresa = 2;  // pendiente de volver variable global
	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["txtNombrePerfil"])&&isset($_POST["rol"])) {	
		$resultado = $prf->insertAll($_POST, $Id_Empresa);
		if($resultado != "OK")
			$msgError = "<script>alert('Error durante el proceso de creación del Perfil {$_POST["txtNombrePerfil"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('El Perfil {$_POST["txtNombrePerfil"]} ha sido creado correctamente')</script>";
	} 
	
?>
<html>
	<head>
	</head>	
	<body>
		<p>
		<form method="post">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;PERFIL&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
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
<?php

?>