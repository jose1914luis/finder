<?php
	@session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/IndexacionesQueries.php");
	
	if (isset($_POST["query"]) && $_POST["query"] != "") {
		
		$indexar 			= new IndexacionesQueries();
		$listadoRegistros 	= $indexar->selectDocumentQueryByListaCampos($_POST["query"]);
		
		$titulo				= "Search - Results";	
		$clasificacion 		= "";

		if(!empty($listadoRegistros)){		
			$nroRegistros 	= sizeof($listadoRegistros);
			$nroColumnas  	= sizeof($listadoRegistros[0]);
			
			// Para resaltar las palabras claves
			$paraReemplazar = explode(" ", $_POST["query"]);
			//foreach($paraReemplazar)
			
				
			$tablaSol  = "<table border='1' bgcolor='#FFFFFF'><tr bgcolor='#06526F'><td colspan='$nroColumnas'>
				<font color='#ffffff'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".strtoupper($titulo)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N&uacute;mero de Registros: $nroRegistros </font></td></tr>";			
			$tablaSol .= "<tr><td align='center' bgcolor='#f8f8f8'><b>REPORTE</b></td>";
			
			$titulos_tabla = $listadoRegistros[0];
			$idfrm = array_shift($titulos_tabla);	
			
			foreach($titulos_tabla as $k=>$v)
				$tablaSol .= "<td align='center' bgcolor='#f8f8f8'><b>".strtoupper(str_replace("_"," ",$k))."</b></td>";
			$tablaSol .= "</tr>";	
			
			for($i=0;$i<$nroRegistros;$i++) {
				$idfrm = array_shift($listadoRegistros[$i]);
				$enlace = "&nbsp;<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa=".$listadoRegistros[$i]["placa"]."&idfrm=$idfrm', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='30' height='30' title='Generaci&oacute;n de Reporte para ".$listadoRegistros[$i]["placa"]."'></a>";

				$tablaSol .= "<tr><td align='center'><b>$enlace</b></td>";
				foreach($listadoRegistros[$i] as $k=>$v)
					$tablaSol .= "<td>".($v)."</td>";
					
					//"<span style=\"background-color:yellow\">hola<\/span>"
					
				$tablaSol .= "</tr>";	
			}	
			$tablaSol .= "</table>";
			echo $tablaSol;
			
		}
			else
				echo "<hr size=1><h3><strong>$titulo: </strong>No existen registros</h3>";
	}	
	
	
?>