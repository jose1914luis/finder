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
	if (isset($_POST["placa"]) && $_POST["placa"] != "") {
		$placa 					= $_POST["placa"];
		$documento 				= new DocumentManagement();
		$listadoCronologico 	= $documento->selectChronologyByPlacaEmpresa($placa, $Id_Empresa);
	}	
	
	// print_r($listadoCronologico);
	if(!empty($listadoCronologico)) {
	
		$rutaImagen		= "Imagenes/Cronologia/"; 
		$cadaDocumento 	= array_shift($listadoCronologico);	
		$fechaActual 	= $cadaDocumento["fecha_imagen"];
		$tablaFechas 	= "<td rowspan='2' background='Imagenes/Cronologia/separador.gif'>&nbsp;</td><td><b>$fechaActual</b><br><hr size='0'></td><td rowspan='2' background='Imagenes/Cronologia/separador.gif'>&nbsp;</td>";
		$tablaImagenes 	= "<td><a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa=$placa&idfrm={$cadaDocumento["id_documento"]}', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='$rutaImagen".$documento->getImageIcon($cadaDocumento["nombre"])."' border='0' title='$placa :: {$cadaDocumento["nombre"]}'/></a><p>";	
		
		
		foreach($listadoCronologico as $cadaDocumento) {
			if($fechaActual != $cadaDocumento["fecha_imagen"]) {
				$fechaActual = $cadaDocumento["fecha_imagen"];
				$tablaFechas .= "<td><b>$fechaActual</b><br><hr size='0'></td><td rowspan='2' background='Imagenes/Cronologia/separador.gif'>&nbsp;</td>";
				$tablaImagenes .= "</td><td>";
			}
			$tablaImagenes .= "<a href='javascript:' onclick=\"window.open('management.expediente.report.c.php?placa=$placa&idfrm={$cadaDocumento["id_documento"]}', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='$rutaImagen".$documento->getImageIcon($cadaDocumento["nombre"])."' border='0' title='$placa :: {$cadaDocumento["nombre"]}'/></a><p>";
		}	
		$tablaImagenes .= "</td>";
		
		$tablaSol = "<table border='0' align='left'><tr align='center'>$tablaFechas</tr><tr align='center' valign='middle'>$tablaImagenes</tr></table>";
	} else 
		$tablaSol = "<table border='0' align='center'><tr><td align='center'><i><b>Data Not Found</b></i></td></tr></table>";
	
	$expedienteConsultado = "<h2>Chronology. Aplication/Title Code: $placa</h2><hr size='0'><p>";
	
	echo $expedienteConsultado.$tablaSol;
	
?>