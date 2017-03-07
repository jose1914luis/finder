<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Modelos/CreditosUsuarios.php");
	require_once("Modelos/ReportGenerator.php");	
	
	if(!empty($_SESSION["id_usuario"])&&@$_GET["placa"]!="") {
		$idProductoCred = 5;
		$msgSistema		= "";
		
		// para ver productos ya pagos
		if(@$_GET["creditos_prod"]>0) { 
			$msgSistema	= $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);
		} else if(@$_GET["credits"]==1)	{	
			$cred 		= new CreditosUsuarios();
			$msgSistema	= $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $_GET["placa"]);
		} else
			echo "<script>	window.close();	</script>";			
		
		if($msgSistema=="OK")	{		
			// Generación del estudio técnico del prospecto
			$consulta = new ReportGenerator();
			$consulta->ejecutarEstudiosTecnicosProspectos($_SESSION["idEmpresa"], $_GET["placa"]);
		
			// id de producto de descarga de shape de area libre
			$idSession = md5(time());
			// Nombre del archivo de descarga:
			$archivoDescarga = "DwnShapes/shpAreaLibre_Bog_".$idSession.".zip";
			// Hay que configurar el pg_hba en md5 para que esto funcione
			// local   all             all                                peer-->md5
			$salida = shell_exec("./scriptDownloadRALShp.sh {$_GET["placa"]} $idSession");					
			
			// echo "<pre>$salida</pre>";
			// echo "<hr><a href='$archivoDescarga'>Descarga de Shape</a>";
			echo "<script>window.opener.document.location.reload(); document.location.href='$archivoDescarga'</script>";
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

