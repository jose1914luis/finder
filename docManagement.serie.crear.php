<?php
	/*
		session_start();

		
		require_once("Acceso/Config.php"); // Definición de las variables globales	
		require_once("Modelos/Perfiles.php");
		require_once("Modelos/Acciones.php");
		
		$acciones = new Acciones();
		$prf 	= new Perfiles();
	*/

		// variables del controlador	
		$msgError = "";
		$Id_Empresa = 2;  // pendiente de volver variable global

	/*	
		//$accionPage = new SeguimientosUsuarios;
		//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
		if(isset($_POST["txtNombrePerfil"])&&isset($_POST["rol"])) {	
			$resultado = $prf->insertAll($_POST, $Id_Empresa);
			if($resultado != "OK")
				$msgError = "<script>alert('Error durante el proceso de creación del Perfil {$_POST["txtNombrePerfil"]}. $resultado')</script>";
			else
				$msgError = "<script>alert('El Perfil {$_POST["txtNombrePerfil"]} ha sido creado correctamente')</script>";
		} else 	{
			$msgError = "<script>alert('La información se encuentra incompleta para crear el perfil')</script>";
		}

	*/	
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
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR SERIE&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td width="25%">Nombre de la Serie: </td>
				<td><input type="text" name="txtNombreSerie" size=15></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="button" value="Crear Serie">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="Limpiar Formulario">
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