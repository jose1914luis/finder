<?php
	@session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/DocumentManagement.php");
	require_once("Modelos/Usuarios_SGM.php");
	
	//validación de usuarios en CMQ
	$validate = new Usuarios_SGM();	
	$Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);	
	if(empty($Id_Empresa) || !$Id_Empresa) echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";

	$placa = "";
	if (isset($_POST["placa"]) && $_POST["placa"] != "") 	$placa 	= strtoupper($_POST["placa"]);
	else													$placa  = "";
	
	$documento 				= new DocumentManagement();
	$listadoAlertas 		= $documento->selectAlertByPlacaEmpresa($placa, $Id_Empresa);

	
	// print_r($listadoAlertas);
	if(!empty($listadoAlertas)) {
		$rutaImagen		= "Imagenes/Cronologia/"; 
		$tablaResults = "
			<table border='1' align='left' cellpadding='5' cellspacing='0'>
				<tr><td colspan='5' bgcolor='#000033' align='center'><h2><font color='white'>Alerts System for Aplication(s)/Title(s)</font></h2></td></tr>
				<tr align='center' bgcolor='#efefef'>
					<th>Aplication/Title</th>
					<th>Request Date</th>
					<th>Compliance Date</th>
					<th>Expiration Date</th>
					<th>Pending</th>
				</tr>
		";
		
		foreach($listadoAlertas as $cadaAlerta) {
			$tablaResults .= "
					<tr align='center'>
						<td valign='middle'>
							<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa={$cadaAlerta["expediente"]}&idfrm=0', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='$rutaImagen"."expediente_icon.gif"."' border='0' title='Aplication/Title {$cadaAlerta["expediente"]}'/></a>
							<br><b>{$cadaAlerta["expediente"]}</b>
						</td>
						<td valign='top'>
							{$cadaAlerta["fecha_imagen_requiere"]}<hr size=0>
							<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa={$cadaAlerta["expediente"]}&idfrm={$cadaAlerta["id_doc_requiere"]}', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='$rutaImagen".$documento->getImageIcon($cadaAlerta["nombre_requiere"])."' border='0' title='{$cadaAlerta["expediente"]} :: {$cadaAlerta["nombre_requiere"]}'/></a>
						</td>
						<td valign='top'>
							{$cadaAlerta["fecha_imagen_resuelve"]}<hr size=0>
							<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa={$cadaAlerta["expediente"]}&idfrm={$cadaAlerta["id_doc_resuelve"]}', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='$rutaImagen".$documento->getImageIcon($cadaAlerta["nombre_resuelve"])."' border='0' title='{$cadaAlerta["expediente"]} :: {$cadaAlerta["nombre_resuelve"]}'/></a>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>				
					</tr>
			";		
		}
		$tablaResults .= "
			</table>
		";
	} else {
		$tablaResults = "
			<table border='0' align='left'>
				<tr><td bgcolor='#000033'>ALERTS SYSTEM BY APLICATION/TITLE</td></tr>
				<tr align='center'>
					No Data Found
				</tr>
		";	
	}
	
	echo $tablaResults;
	
?>