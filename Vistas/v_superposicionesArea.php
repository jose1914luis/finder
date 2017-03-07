<?php


	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/ReportGenerator.php");
	require_once("Modelos/CreditosUsuarios.php");	

	$cred 			= new CreditosUsuarios();
	$generarReporte	= 0;
	$idProductoCred	= 8;  // id del reporte de superposiciones
	$placa 			= $_GET["placa"]; 
	
	if(empty($_SESSION['usuario_sgm']))
		header("location: index.php");


	// para ver productos ya pagos
	if(@$_GET["creditos_prod"]>0) { 
		$msgSistema	= $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);
		if($msgSistema =="OK") 
			$generarReporte = 1;
	}

	// Validación de créditos consumidos
	if(@$_GET["credits"]==1 && $idProductoCred > 0) {
		$msgSistema	= $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $placa);
		if($msgSistema=="OK")	
			$generarReporte = 1;
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

	// variables del controlador	
	$msgError 	= "";
	$msgProceso	= "";
	$tabla 		= "";
	$Id_Empresa = $_SESSION["idEmpresa"];  
	
	$prospectoSGM = new ProspectosBogSGM();

	
	if($_GET["placa"]!="") {				
	
		$resultado = "OK"; //$resultado = $prospectoSGM->insertAll($_POST, $Id_Empresa, $_SESSION["id_usuario"]);
		$centroides = $prospectoSGM->getCentroideWGS84($placa);
		
		$areaPerimetro = $prospectoSGM->getArea($placa);
		$areaPoly = $areaPerimetro["area"];
		$perimetroPoly = $areaPerimetro["perimetro"];
		
		if($resultado == "OK") {
			$consultar = new ReportGenerator();
			
			// Generación de análisis de superposiciones del prospecto
			$tipo_analisis = "PROSPECTO";
			$tipoEstudio = "ESTUDIO_TECNICO_PROSPECTO";
			$listadoEstudio = $consultar->selectEstudiosTecnicosProspectos($Id_Empresa, $placa);
				
			if(!empty($listadoEstudio)){		
				$nroSolicitudes = sizeof($listadoEstudio);
				$nroColumnas = sizeof($listadoEstudio[0]) + 1;				
?>
<html>
	<head>
		<link rel="stylesheet" href="Javascript/sigmin_account.css">
	</head>
	<body>
		<div>&nbsp;</div>
		<table class="results" align="center" width="95%" border="1">
			<caption>
				<div class="titleSite" style="text-align:center">REPORTE DE SUPERPOSICIONES - N&uacute;mero de superposiciones: <?=$nroSolicitudes?></div>
				<div>&nbsp;</div>				
			</caption>
			<tbody>			
				<tr class="results">
					<th align='center' class="results"><b>REPORTE</b></th>
<?php			
				foreach($listadoEstudio[0] as $k=>$v) {
?>
					<th align='center' class="results"><b><?php echo strtoupper(str_replace("_"," ",utf8_decode($k)))?></b></th>
<?php					
				}
?>
				</tr>	

<?php				
				for($i=0;$i<$nroSolicitudes;$i++) {
					$URL_Acceso = "?crd=expediente&placa={$listadoEstudio[$i]["expediente_superpone"]}&clasificacion={$listadoEstudio[$i]["tipo_superposicion"]}";					
					
					$enlace = "<a href='javascript:' onclick=\"window.open('$URL_Acceso', '_blank');\"><img src='Imgs/reportIcon.png' border='0' width='35' height='35' title='Generaci&oacute;n de Reporte para ".$listadoEstudio[$i]["expediente_superpone"]."'></a>";
?>					
					<tr class="results">
						<td align='center' class="results"><b><?=$enlace?></b></td>
<?php
					foreach($listadoEstudio[$i] as $k=>$v) {
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
				<div>&nbsp;</div>				
<?php				
			} else {
?>
					<hr size='0'>
					<center><h1>No existen superposiciones<br>para el prospecto <?=$placa ?></h1></center>
					<hr size='0'>
<?php
			}
	
		} else {
?>
			<script>alert('::ERROR:: El prospecto <?=$placa ?> no fue generado satisfactoriamente');	window.close();	</script>
<?php			
		}
	} else {
?>
		<script>alert('::ERROR:: No existe prospecto de referencia para evaluar'); 	window.close();	</script>";
<?php		
	}

	if(@$_GET["credits"]==1) {
?>
		<script>window.opener.document.location.reload();</script>	
<?php
	}
?>
	</body>
</html>	