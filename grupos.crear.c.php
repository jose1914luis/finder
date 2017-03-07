<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Perfiles.php");
	require_once("Modelos/Grupos.php");
	require_once("Modelos/Usuarios_SGM.php");
	
	$usr = new Usuarios_SGM();
	$prf = new Perfiles();
	$grp = new Grupos();

	// variables del controlador	
	$msgError = "";
	$Id_Empresa = 2;  // pendiente de volver variable global
	
	// Listados de Perfiles y de Usuarios
	$listaUsuarios = $usr->selectByIDEmpresa($Id_Empresa);
	$listaPerfiles = $prf->selectByIdEmpresa($Id_Empresa);
	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	if(isset($_POST["txtNombreGrupo"])&&isset($_POST["selUsuario"])&&isset($_POST["selPerfil"])) {	
		$resultado = $grp->insertAll($_POST, $Id_Empresa);
		if($resultado != "OK")
			$msgError = "<script>alert('Error durante el proceso de creación del Grupo {$_POST["txtNombreGrupo"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('El Perfil {$_POST["txtNombreGrupo"]} ha sido creado correctamente')</script>";
	} 
	
?>

<html>
	<head>
	</head>	
	<body>
		<p>
		<form method="POST">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
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
