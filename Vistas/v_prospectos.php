<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 

	if(!empty($_SESSION["id_usuario"])) {
		$prp 		= new ProspectosBogSGM(); 
		$generaURL	= new ControlPopups();	
		
		if(empty($_GET["pagina"])) {
			$prospects 									= $prp->selectProspectosByUsuario($_SESSION["id_usuario"]);
			$clasificacion 								= "PROSPECTO";			

			// Para paginación del resultado
			$max_por_pagina 							= $GLOBALS ["max_por_pagina"];	
			$_SESSION["lista_prospectos"]["paginas"] 	= array_chunk($prospects, $max_por_pagina); 
			$_SESSION["lista_prospectos"]["caption"] 	= "Reporte de Prospectos";	
		}			

		$total_paginas 		= sizeof($_SESSION["lista_prospectos"]["paginas"]);	

		$pagina_actual 		= (@$_GET["pagina"] > 1) ? 	$pagina_actual = $_GET["pagina"] : 1;
		$dataSET			= $_SESSION["lista_prospectos"]["paginas"][$pagina_actual - 1];
		
		// generación de listado de paginación, paginas por pantalla:
		$pags_pantalla		= $GLOBALS ["pags_pantalla"];
				
		function generaPaginacion($url, $pags_pantalla, $pagina_actual, $total_paginas) {			
			$pagInicial	= floor(($pagina_actual-1)/$pags_pantalla)*$pags_pantalla + 1;
			$pagFinal	= ($pagInicial+$pags_pantalla-1>$total_paginas)? $total_paginas : $pagInicial+$pags_pantalla-1;

			$pagAnt		= ($pagInicial-1<1) ? 1 : $pagInicial-1;
			$pagSte		= ($pagFinal+1 > $total_paginas) ?  $total_paginas : $pagFinal+1; 
			
			$paginacion = '<ul class="pagination"><li><a href="'.$url.'&pagina='.$pagAnt.'">«</a></li>';
			for($i=$pagInicial; $i <= $pagFinal; $i++) {
				$activar = '';
				if($i==$pagina_actual) $activar=' class="active" ';
				$paginacion .= '<li><a '.$activar.' href="'.$url.'&pagina='.$i.'">'.$i.'</a></li>'; 
			} 
			$paginacion .= '<li><a href="'.$url.'&pagina='.$pagSte.'">»</a></li></ul>';
			return 	$paginacion;
		}

		// fin de variables de paginación del resultado
		if(!empty($dataSET))	$tfoot = generaPaginacion("?mnu=prospectos", $pags_pantalla, $pagina_actual, $total_paginas, $max_por_pagina);
		else					$tfoot = "<h2>No hay prospectos definidos</h2>";

		$tablaProspectos = '
			<table class="table table-striped" align="center" width="95%">
				<caption>
					<div class="titleSite" style="text-align:center">Reporte de Prospectos</div>
					<div>&nbsp;</div>				
				</caption>
				<tfoot>
					<tr>
						<td colspan="6" align="center">
							<ul class="pagination">
								'.$tfoot.'
							</ul>					
						</td>
					</tr>
				</tfoot>				
				<tbody>
				<tr class="results">
					<th class="results">REPORTES</th>
					<th class="results">ELIMINAR</th>
					<th class="results">PLACA</th>
					<th class="results">FECHA CREACION</th>			
					<th	class="results">AREA Has</th>
					<th class="results">MUNICIPIOS</th>
				</tr>
		';		
		if(!empty($dataSET)) {
			//foreach( $prospects as $cadaProspecto) {
			foreach( $dataSET as $cadaProspecto) {				
				$enlace = "<center><a href='javascript:' onclick=\"loadCreditosProspectos('{$cadaProspecto["placa"]}')\"><img src='Imgs/reportIcon.png' border='0' width='30' height='30' title='Generaci&oacute;n de Reporte para ".$cadaProspecto["placa"]."'></a></center><div id='{$cadaProspecto["placa"]}' class='creditos' style='display: none;' ></div>";			
				
				$tablaProspectos .= "
					<tr class='results'>
						<td class='results'>$enlace</td>
						<td class='results'><center><a href='javascript:' onclick=\"eliminarProspecto('{$cadaProspecto["placa"]}')\"><img src='Imgs/deleteProspectIcon.png' border='0' width='30' height='30' title='Eliminar prospecto ".$cadaProspecto["placa"]."'></a></center></td>
						<td class='results'>{$cadaProspecto["placa"]}</td>
						<td class='results'>{$cadaProspecto["fecha_creacion"]}</td>			
						<td class='results'>{$cadaProspecto["area_has"]}</td>
						<td class='results'>{$cadaProspecto["municipios"]}</td>
					</tr>			
				";				
			}

		} 	
		$tablaProspectos .=  "</tbody></table>";		
?>
	<div>

		<form name="prospectos" action="?mnu=prospectos" method="post">
			<?=$tablaProspectos; ?>
			</br>
			<input type="hidden" name="placa" value="" />
			<input type="hidden" name="act" value=""/>
		</form>

	</div>	

<?php		
	} else {
?>
	<center><h2>Error inesperado en el sistema</h2></center>
<?php		
	}
	
?>

