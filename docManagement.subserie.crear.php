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
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR SUBSERIE&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				</td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>
			</tr>
			<tr>
				<td width="25%">Serie: </td>
				<td>
					<select name="selSerie">
						<option value="0">Seleccione la serie
						<option value="1">Radicaciones
						<option value="2">Edictos
					</select>
				</td>
			</tr>
			<tr>
				<td width="25%">Nombre de Subserie: </td>
				<td><input type="text" name="txtNombreSubserie" size=45></td>
			</tr>
				<td colspan=2 align="center">
					<hr size=1/>
				</td>			
			<tr>
				<td>
					Nombre del Campo:<br>
					<input type="text" name="txtCampo">
					<p>
					Tipo de Campo
					<select name="selTipoDato">
						<option value=1>Texto Corto
						<option value=2>Texto Largo
						<option value=3>Entero
						<option value=4>Flotante
						<option value=5>Fecha
						<option value=6>Lista Simple
						<option value=7>Lista Múltiple						
					</select>
					<p>
					Obligatorio: &nbsp;
					<select name="selDatoObligatorio">
						<option value=1>SI
						<option value=0>NO
					</select>
					<p>
					<input type=button value="Anexar Campo ->>">
				</td>
				<td>
					Campos Generados:<br>
					<select name="selDatoObligatorio" multiple size=7>
						<option value=1>Codigo Expediente
						<option value=2>Fecha Radicación
						<option value=3>Proponentes
						<option value=4>Minerales
						<option value=5>Modalidad
						<option value=6>Grupo de Trabajo
					</select>				
					<p>
					<input type=button value="<<- Eliminar Campo">
					
				</td>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="button" value="Crear Subserie">
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