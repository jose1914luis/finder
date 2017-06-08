<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/CreditosUsuarios.php");
	require_once("Modelos/DescargarShapes.php");
	
	if(!empty($_SESSION["id_usuario"])&&@$_GET["placa"]!="") {
		$idProductoCred = 4;
		$msgSistema		= "";
		
		// para ver productos ya pagos
		if(@$_GET["creditos_prod"]>0) { 
			$msgSistema	= $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);
		} else if(@$_GET["credits"]==1)	{	
			$cred 		= new CreditosUsuarios();
			$msgSistema	= $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $_GET["placa"]);
		} else
			echo "<script>	window.close();	</script>";			
		
		if($msgSistema=="OK")	{		$placas = $_GET["placa"];			
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
	<script>alert("Error inesperado en el sistema");</script>
<?php		
	}
	
?>

