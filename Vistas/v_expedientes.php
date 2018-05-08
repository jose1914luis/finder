<script>
	function validarBusqueda() { 
		if(document.forms["searchWords"].txtBusqueda.value=="")  
			return 0;
		$.post('viewServicesSIGMINFullResultados.php', { txtBuscar : document.forms["searchWords"].txtBusqueda.value}, function(resp) {
				if(resp!="")  {					
					$("#resultados").html(resp);
				} else
					alert("No hay retorno de información");
			});				
	}
	 $(function(){
		$("#txtBusqueda").autocomplete({
		   source: "viewValidaPlaca.php"
		});		
	});
</script>

<div id="resultados">
	<table border="0" width="90%" align="center">
		<tr bgcolor="#dedede">
			<td class="titleSite" align="center" colspan="4">Expedientes Asociados</td>
		</tr>	
		<tr>
			<td colspan="4">
				<div class="ui-widget">
					<div>&nbsp;</div>
					<form name="searchWords" method="post">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expediente/Placa: &nbsp;&nbsp;&nbsp;						
						<input id="txtBusqueda" name="txtBusqueda" size="15">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nombre del proyecto: &nbsp;&nbsp;&nbsp;
						<input id="txtNombrePry" name="txtNombrePry" size="25" maxlength="25">							
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="<< Adicionar">
					</form>

				</div>			
			</td>
		</tr>	
		<tr>
			<td align="center" colspan="4"><hr size="0"></td>			
	<?php		
		$nroExpediente = 0;
		if(!empty($listaExpedientes))
		foreach($listaExpedientes as $cadaExpediente) {
			if($nroExpediente%4==0)	echo '</tr><tr><td align="center" colspan="4">&nbsp;</td></tr><tr>';	
			
			if($cadaExpediente["tipo_expediente"]=="SOLICITUD") 	$img = "folder_sol.png";	
			else if ($cadaExpediente["tipo_expediente"]=="TITULO")	$img = "folder_tit.png";
			else													$img = "";
	?>
		<td align="center" class="styleExp"><a href="?mnu=expedientes_placa&placa=<?=$cadaExpediente["placa"]?>&clasificacion=<?=$cadaExpediente["tipo_expediente"]?>" target="_blank"><img src="Imgs/<?=$img?>" title="Ver <?=$cadaExpediente["tipo_expediente"]?> <?=$cadaExpediente["placa"]?>"></a><br/><?=($cadaExpediente["proyecto"]!="")?strtoupper($cadaExpediente["proyecto"]):$cadaExpediente["placa"]?>&nbsp;<a href="javascript:" class="styleExp" title="Inactivar Expediente" onclick="inactivarPlaca('<?=$cadaExpediente["placa"]?>')">[X]</a></td>
	<?php
		$nroExpediente++;
		}
	?>
		</tr>
	</table>
</div>	
</br>