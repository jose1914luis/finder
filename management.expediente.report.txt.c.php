<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/DocumentManagement.php");	
//	require_once("Modelos/SeguimientosUsuarios.php");	
//	require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
	$msgAcceso = "";
	
	if (isset($_POST["idDocumento"]) && $_POST["idDocumento"]!="") {
		$dManagement	= new DocumentManagement();
		$documentos 	= $dManagement->selectTextDocumentByFormDocument($_POST["idDocumento"]);
		
		echo "
			<html>
			<head>
				<style type=\"text\/css\">
				<!--
				.Estilo1 {
				font-weight: bold;
				font-size: 18px;
				}
				-->
				</style>			
			</head>
			<body>
				<table border='1' align='center' width='90%'>
				<tr bgcolor='fff3f3'>
					<th align='center' colspan='2' class='Estilo1'>".strtoupper($documentos[0]["formulario"])." - EXPEDIENTE ".strtoupper($documentos[0]["placa"])."</th>
				</tr>
				<tr bgcolor='fbfbfb'>
					<th align='center'>INDICE</th>
					<th align='center'>INFORMACI&Oacute;N</th>
				</tr>
		";	
		
		foreach($documentos as $cadaDocumento) {
			echo "
				<tr>
					<td align='center'>{$cadaDocumento["indice"]}</td>
					<td align='justify'>{$cadaDocumento["dato"]}</td>
				</tr>			
			";		
		}
		echo "	</table></body></html>";
				
	} 
		//header("Location: DocumentosElectronicos/r2d2DocumentManagement.pdf"); 
	
?>	

