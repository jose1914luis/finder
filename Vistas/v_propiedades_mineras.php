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
		   source: "viewValidaQuery.php"
		});		
	});
</script>

<div class="ui-widget">

	<form name="searchWords" action="?mnu=expedientes_rsl" method="post">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Consulta multicriterio: &nbsp;&nbsp;&nbsp;
		<!-- <input type="text" name="txtBusqueda" id="txtBusqueda"> -->
		<input id="txtBusqueda" name="txtBusqueda" size=50 placeholder="  General Search...">		
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Buscar" onclick="validarBusqueda()">
		<hr size="0">
	</form>

</div>

<div id="resultados">

</div>	
</br>