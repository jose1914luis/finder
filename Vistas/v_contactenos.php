	<script>
		function enviarForm() {		
			if(document.frmContactenos.txtObservacion.value.trim()=="") return 0;
			document.frmContactenos.submit();
	    } 
			
	</script>
	
<form name="frmContactenos" method="post" action="?mnu=contactenos">
	<table width="95%" border="0" cellspacing="0" align="center" class="tableFonts">
		<tr>
			<td class="titleSite" align='center'>Zona PQR's - Preguntas, Quejas y Reclamos</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>	
		<tr class="results">
			<td class="results">
			<p>Apreciado(a) <b><?=$_SESSION['usr_cred']['nombre']?></b>.</p>
			<p align="justify">Para nosotros es importante recibir sugerencias y retroalimentaci&oacute;n de parte de nuestros usuarios, por ello hemos dispuesto el siguiente campo a fin de conocer su opini&oacute;n sobre nuestro sistema, como sugerencias, quejas o reclamos que considere informar.</p>
			<p align="justify">Agradecemos la confianza que ha depositado en nosotros como sus valiosos comentarios y esperamos que todo nuestro sistema sea de gran utilidad en su b&uacute;squeda y adquisici&oacute;n de nuevas oportunidades mineras.</p>
			</td>
		</tr>
		<tr class="results">
			<td class="results">
				<center><textarea name="txtObservacion" rows="6" cols="70" placeholder="Ingrese aquí sus comentarios y sugerencias a fin de mejorar nuestra plataforma"></textarea></center>
			</td>
		</tr>	
		<tr class="results">
			<td colspan="2" align="center" class="results">
				<hr size="0">
				<center><input type="button" name="type" id="cambioClave" title="Envío de sugerencias al equipo técnico de SIGMIN S.A.S" value="Enviar Sugerencia" onClick="enviarForm()"/></center>
				<hr size="0">
			</td>
		</tr>
	</table>	
</form>	
