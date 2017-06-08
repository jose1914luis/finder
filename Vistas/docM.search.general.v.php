<html>
	<head>
	<script type="text/javascript" src="Utilidades/jquery.min.js"></script>
	<script>
		// variables globales
		var winP=null, resultados=null;
	

		function consultarIndexamiento() { 
			strQuery = document.forms[0].searchQuery.value;
			if(strQuery=="")  
				return 0;
			$.post('viewDocuments.php', { query : strQuery }, function(resp) {
				if(resp!="") 	
					$("#queryResults").html(resp);
				else
					alert("Search Index: Data not found !!!");
			});				
		}
	
	</script>	
	</head>
		<body>
			<p>
			<form name "frmSearchGral" method="POST">
			<input type="hidden" name="operacionForm" value="empresas.crear">
			<table align="center" width="100%">
				<tr>
					<td colspan=2 align="center">
						<div style="border-style: solid; border-color: #000048; border-width: 2px"><b><font color='black'>::&nbsp;&nbsp;&nbsp;&nbsp;SIGMIN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;CONSULTA&nbsp;&nbsp;&nbsp;&nbsp;GENERAL&nbsp;&nbsp;&nbsp;&nbsp;::</font></b></div>						
					</td>
				</tr>			
				<tr>
					<td colspan=2 align="center">
						<hr size=1/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td><input type="text" name="searchQuery" size="60">&nbsp;&nbsp;&nbsp;<input type="button" name="Submit" value=" Search " onclick="consultarIndexamiento()"></td>
				</tr>
				<tr>
					<td colspan='2'><hr size=0></td>
				</tr>								
			</table>
			</form>
		<div id="queryResults"></div>
	</body>
</html>
