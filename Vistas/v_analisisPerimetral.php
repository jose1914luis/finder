<?php
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/AnalisisVecinos.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 	
	require_once("Modelos/CreditosUsuarios.php");
	
error_reporting(E_ALL);
ini_set('display_errors', '1');		
	
	// variables del controlador	
	$msgError 	= "";
	$reporte 	= new ProspectosBogSGM();
	$vecino		= new AnalisisVecinos();
	$generaURL	= new ControlPopups();	
	$tabla 		= "";
	$idVecino	= 0;
	$idEmpresa	= $_SESSION["idEmpresa"];
	$errVecino	= "OK";
	//$_SESSION["myExcelFile"] = "";
	

	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(empty($_GET["creditos_prod"]) && (@$_POST["txtRadio"]<=0||@$_POST["txtRadio"]>15000))
		echo $msgProceso = "<script>
								alert('::ERROR:: A superado el rango permitido para análisis perimetral de 15000 mts'); 
								close();
							</script>";		
	else if(@$_POST["coordenadasRAC"]!="" || (!empty($_GET["placa"])&&!empty($_GET["creditos_prod"]))) {
		
		$cred 			= new CreditosUsuarios();
		$generarReporte	= 0;
		$idProductoCred	= 12;  // id del reporte de superposiciones
		
		if(empty($_SESSION['usuario_sgm']))
			header("location: index.php");


		// para ver productos ya pagos
		if(@$_GET["creditos_prod"]>0) { 
			$msgSistema	= $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);
			if($msgSistema =="OK") {
				$idVecino = @$_GET["placa"];
				$generarReporte = 1;
			}
		}

		// Validación de créditos consumidos
		if(@$_GET["credits"]==1 && $idProductoCred > 0) {
			$idVecino 		= $vecino->selectIdVecino();
			$placa 			= "$idVecino"; 			
			
			$msgSistema	= $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $placa);
			if($msgSistema=="OK") {	
				$errVecino = $vecino->insertAll($idVecino, $idEmpresa, "BOGOTA", $_POST["coordenadasRAC"]);			
				$generarReporte = 1;
			}	
		}

		if(!$generarReporte) {
			if(trim($msgSistema)=="") $msgSistema = "Error inesperado al procesar petici\u00F3n";
			echo "
				<script>
					alert('$msgSistema');
					window.close();
				</script>
			";	
		}		
		
		if($errVecino != "OK") echo "<hr>$errVecino<hr>";
		
		$listadoSuperposiciones = $reporte->get_SuperposicionByNeighbor($idVecino);
		
		if(!empty($listadoSuperposiciones)){		
			$nroSuperposiciones = sizeof($listadoSuperposiciones);
			$nroColumnas = sizeof($listadoSuperposiciones[0]) + 1;
			
?>
<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>	
		<link rel="stylesheet" href="Javascript/sigmin_account.css">
	</head>
	<body>
			<div>&nbsp;</div>	
			<table class="results" align="center" width="95%" border="1">
				<caption>
					<div class="titleSite" style="text-align:center">AN&Aacute;LISIS PER&Iacute;METRAL - N&uacute;mero de superposiciones: <?=$nroSuperposiciones?></div>
					<div>&nbsp;</div>				
				</caption>	
				<tbody>					
					<tr class="results">
						<th align='center' class="results"><b>REPORTE</b></th>

<?php			
			foreach($listadoSuperposiciones[0] as $k=>$v) {
?>
						<th align='center' class="results"><b><?php echo strtoupper(str_replace("_"," ",utf8_decode($k)))?></b></th>
<?php							
			}
?>				
					</tr>	
<?php			
			for($i=0;$i<$nroSuperposiciones;$i++) {				
				$URL_Acceso = "?crd=expediente&placa={$listadoSuperposiciones[$i]["expediente_superpone"]}&clasificacion={$listadoSuperposiciones[$i]["tipo_superposicion"]}";					
				$enlace = "<a href='javascript:' onclick=\"window.open('$URL_Acceso', '_blank');\"><img src='Imgs/reportIcon.png' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoSuperposiciones[$i]["expediente_superpone"]."'></a>";				
				
/*				
				$codAcceso = $generaURL->setControlPopup($listadoSuperposiciones[$i]["expediente_superpone"], $listadoSuperposiciones[$i]["tipo_superposicion"]);
				$enlace = "<a href='javascript:' onclick=\"window.open('reporteAreas.php?cod_acceso=$codAcceso', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imgs/reportIcon.png' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoSuperposiciones[$i]["expediente_superpone"]."'></a>";
*/				
?>				
					<tr class="results">
						<td align='center' class="results"><b><?=$enlace?></b></td>
<?php						
				foreach($listadoSuperposiciones[$i] as $k=>$v) {
?>					
						<td class="results"><?php echo utf8_decode($v) ?></td>
<?php					
				}
?>								
					</tr>	
<?php				
			}	
?>			
				</table>	
<?php
		} else
			echo $msgProceso = "<script>
									alert('No hay superposiciones en el \u00E1rea de influencia'); 
									close();
								</script>";		
		
	} else {
		echo $msgProceso = "<script>
								alert('::ERROR:: No existe punto de influencia para evaluar'); 
								close();
							</script>";
	}

	
?>	
	</body>
</html>
