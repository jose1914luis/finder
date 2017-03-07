<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>Crear Proyecto</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript">
			function anexarExpediente() {
				campo = document.forms[0].txtCodigoExpediente.value;
				
				if(document.forms[0].txtExpedientes.value.length)
					document.forms[0].txtExpedientes.value += "\n" + campo;
				else	
					document.forms[0].txtExpedientes.value = campo;
			}

		</script>
	</head>
	<body>
		<p>
		<form method="post">
		<table align=center width="50%">
			<tr>
				<td colspan=2 bgcolor="#000077" align="center">
					<b><font color=white>::&nbsp;&nbsp;&nbsp;&nbsp;CREAR&nbsp;&nbsp;&nbsp;PROYECTO&nbsp;&nbsp;&nbsp;&nbsp;::</font></b>
				<td>
			</tr>			
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
				<td>
			</tr>
			<tr>
				<td>C&oacute;digo de Proyecto:</td>
				<td><input type="text" name="txtCodigoProyecto" onChange="this.value=this.value.toUpperCase()"></td>
			</tr>
			<tr>
				<td>Nombre del Proyecto:</td>
				<td><input type="text" name="txtNombreProyecto" size=45 onChange="this.value=this.value.toUpperCase()"></td>
			</tr>
			<tr>
				<td>C&oacute;digo del Expediente Minero Asociado:</td>
				<td>
					<input type="text" name="txtCodigoExpediente" size=15 onChange="this.value=this.value.toUpperCase();">
					&nbsp;&nbsp;
					<input type="button" name="btnAnexar" value="Anexar >>" onclick="anexarExpediente()">
				</td>
			</tr>
			<tr>
				<td>Listado de Expedientes Asociados:</td>
				<td>
					<TEXTAREA Name="txtExpedientes" rows="7" cols="25" readonly></TEXTAREA> 
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<hr size=1/>
					<input type="submit" value="Crear Proyecto">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" value="Borrar Formulario">
					<hr size=1/>
				<td>
			</tr>				
		</table>
		</form>
		<?php
			if($msgError)
				echo $msgError;
		?>		
	</body>
</html>
