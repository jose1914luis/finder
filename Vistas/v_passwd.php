<script>
	function changePassword() {
		if(validarCreacionUsr()) {
		    $.post('viewChangePasswd.php', { claveOld: document.forms["account"].txtClaveOld.value, claveNew: document.forms["account"].txtClaveNew01.value }, function(resp) {
				    if(resp!="") {
						eval(resp);
				} else
				        alert("No hay retorno de informaci&oacute;n");
			});							
		}
	}
</script>

<form name="account" method="post">
	<table width="95%" border="0" align="center">
		<tr>
			<td colspan="2" bgcolor="#dedede" align='center'><b>Cambio de Clave</b></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>	
		<tr>
			<td>Ingrese Clave Anterior</td>
			<td>
				<input class="txtPasswd" type="password" name="txtClaveOld"  required>
			</td>
		</tr>
		<tr>
			<td>Ingrese Nueva Clave</td>
			<td>
				<input class="txtPasswd" type="password" name="txtClaveNew01" required>
			</td>
		</tr>	
		<tr>
			<td>Nueva Clave Otra Vez</td>
			<td>
				<input class="txtPasswd" type="password" name="txtClaveNew02" required>
			</td>
		</tr>	
		<tr>
			<td colspan="2" align="center">
				<hr size="0">
				<input type="button" name="type" id="cambioClave" title="Aplicar Cambio" value="Cambio de Clave" onClick="changePassword()"/>
				<hr size="0">
			</td>
		</tr>
	</table>	
</form>