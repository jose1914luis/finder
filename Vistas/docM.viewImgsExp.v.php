<html>
<head>
	<script language="javascript" type="text/javascript" src="Utilidades/jquery.min.js"></script>
	<script>
		var listaImagenes = [];
		
		<?php
			$i=1;					
			
			if(!empty($listaImgs))
				foreach($listaImgs as $cadaImg)
					echo "listaImagenes[".($i++)."] = '{$cadaImg["path_file"]}'; \n";
			
		?>
		
		var nroImagenes = (listaImagenes.length > 0) ? listaImagenes.length - 1 : 0;
		var indice = (nroImagenes > 0) ? 1 : 0;
	
		function resizeImg() {
			var imagen		= document.getElementById("panelImg");
			var maxwidth 	= 950; 
			var maxheight = Math.floor(maxwidth*imagen.height/imagen.width);
			
			imagen.width = maxwidth;
			imagen.height = maxheight;
		}	
		
		function goImage() {
			numero = document.forms[0].actualImg.value;
			if(numero >=1 && numero <= nroImagenes) {
				indice = numero;			
				$("#currentImg").html("<img id=\"panelImg\" src=\"" + listaImagenes[numero] + "\">");
				//resizeImg();					
			} else 
				document.forms[0].actualImg.value = indice;
		}
		
		function adelante() {
			if(indice < nroImagenes)			indice++;
			document.forms[0].actualImg.value = indice;
			goImage();
		}
		
		function atras() {
			if(indice > 1) 						indice--;
			document.forms[0].actualImg.value = indice;	
			goImage();
		}
		
		function loadPage() {
			document.forms[0].actualImg.value = 1;
			goImage();		
		}
	</script>
</head>
<body onload="loadPage()">
	<form name="form1">
	<table align="center" width="95%" border='1'>
		<tr>
			<td>
				<table align="center" width="100%" border='0'>
					<tr>
						<td align="left" width="10%">
							&nbsp;&nbsp;<a href="javascript:" onclick="atras()" title="backward"><b>&lt;&lt;</b></a>
						</td>
						<td align="center" width="40%">
							<b>Total Images: <script>document.write(nroImagenes)</script></b>
						</td>					
						<td align="center" width="40%">
							Actual Image: <input type="text" name="actualImg" size="3" value="1"> &nbsp;&nbsp;
							 <input type="button" name="btnGo" value="View..." onclick="goImage()"> 
						</td>					
						<td align="center" width="10%">
							&nbsp;&nbsp;<a href="javascript:" onclick="adelante()" title="Forward"><b>&gt;&gt;</b></a>
						</td>										
					</tr>
					<tr>
						<td colspan=4 align="center">
							<hr size="0">
						</td>
					</tr>						
					<tr>
						<td colspan=4 align="center">
							<div id="currentImg"><img id="panelImg" src="DigitalDocs/r2d2DocumentManagement.jpg"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
	</form>
</body>
</html>