<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/CreditosUsuarios.php");	
	require_once("Modelos/DescargarShapes.php");	


	error_reporting(E_ALL);
	ini_set('display_errors', '1');	

	
	if(!empty($_SESSION["id_usuario"])&&(@$_POST["txtPlacas"] || @$_GET["placa"] )) {
		$idProductoCred = 4;
		$msgSistema		= "";

		
		// conteo de creditos a consumir
		$shpFiles 		= new DescargarShapes;
		$credito 		= $cred->costoCreditoByProducto($idProductoCred);
		
		$placas 		= (@$_POST["txtPlacas"]!="") ? $shpFiles->borrarCaracteres($_POST["txtPlacas"]) : $shpFiles->borrarCaracteres($_GET["placa"]);
		$nroPoligonos 	= $shpFiles->contarExpedientesDescargar($placas);
		
		// para ver productos ya pagos
		if(@$_GET["creditos_prod"]>0) { 
			$msgSistema	= $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);			
		} else if(@$_GET["credits"]==1)	{	// generación del producto
			$cred 		= new CreditosUsuarios();
			$msgSistema	= $cred->usarCreditosLista($_SESSION['id_usuario'], $idProductoCred, $placas, $nroPoligonos);
		} else
			echo "<script>	window.close();	</script>";			
		
		if($msgSistema=="OK")	{				
			$idSession = md5(time());
			$shpFiles = new DescargarShapes;
			$resultado = $shpFiles->getShapeListaExpedientesBog($placas, $idSession);
			
			// Nombre del archivo de descarga:
			$archivoDescarga = "DwnShapes/geoSIGMIN_Bog_".$idSession.".zip";
			if($resultado == "O.K") 
				// Hay que configurar el pg_hba en md5 para que esto funcione
				// local   all             all                                peer-->md5
				$salida = shell_exec("./scriptDownloadShp.sh $idSession $placas");		
			
			echo "<script>
					window.opener.document.location.reload();
					document.location.href='$archivoDescarga';
				</script>";
		} else 
			echo "
				<script>
					alert('$msgSistema');
					window.close();
				</script>
			";		
	} else {
?>
	<script>alert("Error inesperado en el sistema"); window.close();</script>
<?php		
	}
	
?>

