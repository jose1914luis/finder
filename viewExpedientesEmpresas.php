<?php
	@session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/IndexacionesQueries.php");
	require_once("Modelos/DocumentManagement.php");
	
	if (isset($_POST["idEmpresa"]) && $_POST["idEmpresa"] != "") {
		
		$folders 			= new DocumentManagement();
		$listadoRegistros 	= $folders->selectExpedientesByEmpresa($_POST["idEmpresa"]);
		
		$titulo				= "Document Management - Digitized Records ";	
		$clasificacion 		= "";

		if(!empty($listadoRegistros))  {		
			$nroRegistros 	= sizeof($listadoRegistros);
			$cols = ($nroRegistros < 4) ? $nroRegistros  : 4;
			
			$tablaSol  = "<table border=1 align='center'><tr><td><table border='0' bgcolor='#FFFFFF'><tr bgcolor='#06526F'><td colspan='$cols'>
				<font color='#ffffff'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$titulo."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Folders: $nroRegistros </font>&nbsp;&nbsp;&nbsp;</td></tr>
				<tr><td colspan='$cols'>&nbsp;</td></tr>
			";			
			
			for($i=0;$i<$nroRegistros;$i++) {
			
				if(!$i%4)	$tablaSol .= "<tr>";
				
				$enlace = "<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa=".$listadoRegistros[$i]["expediente"]."&idfrm=0', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/expedienteDocumentos_dm.gif' border='0' width='78' height='74' title='Reporte del Expediente ".strtoupper($listadoRegistros[$i]["expediente"])."'></a>";

				$tablaSol .= "<td align='center'>$enlace <br/> <b>".strtoupper($listadoRegistros[$i]["expediente"])."</b></td>";
					
				if($i%4 == 3)	$tablaSol .= "</tr>";	
			}	
			$tablaSol .= "</table></td></tr></table>";
			echo $tablaSol;
			
		}
			else
				echo "<hr size=1><h3><strong>$titulo: </strong>No existen registros</h3>";
	}	
	
	
?>