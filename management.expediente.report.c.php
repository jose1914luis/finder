<?php
//	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/DocumentManagement.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios_SGM.php");
	require_once("Modelos/Expedientes.php");
	
	//validación de usuarios en CMQ
//	$validate 	= new Usuarios_SGM();
	$expediente = new Expedientes();
//	
//	$Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);	
//	if(empty($Id_Empresa) || !$Id_Empresa) echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";
	
	$placa = "";
	if (!empty($_GET["placa"])) {
		$documento 	= new DocumentManagement();
		//$idDoc 		= $_GET["idfrm"];
                $idDoc 		= 0;
		$listaMenu 	= $documento->selectFormsByPlaca($_GET["placa"]);
                print_r($listaMenu);
		$placa 		= $_GET["placa"];
				
		if($idDoc) 	$busqueda = $documento->selectFormsByIdDocumento($_GET["idfrm"]);
		
		$accionPage = new SeguimientosUsuarios;
		$accionPage->generarAccion("Consulta al Ebook del Expediente '$placa'");		
	}	
		
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>View Report :: KEEPER</title>
	<style type="text/css">
	<!--
	.Estilo1 {
	color: #672225;
	font-weight: bold;
	font-size: 20px;
	}
	.tituloArea {
		color: #FFFFFF;
		font-weight: bold;
	}
	-->
	</style>
	<script type="text/javascript" src="Utilidades/jquery.min.js"></script>		
	<script>
		function openDigitalDocument(rutaArchivo) {
			document.forms[1].action = "management.expediente.report.pdf.c.php";
			document.forms[1].txtPathFile.value=rutaArchivo;
			document.forms[1].submit();
		}
		
		function openTxtDocument(idDocument) {
			document.forms[1].action = "management.expediente.report.txt.c.php";
			document.forms[1].idDocumento.value=idDocument;
			document.forms[1].submit();
		}	
		
		function openCadastralDocument(placa, clasificacion) {
			url = "http://www.sigmin.co/services/reporteAreasKeeper.php?placa="+placa+"&clasificacion="+clasificacion;
			document.getElementById('digitalDocument').src = url;
		}
		
		function openAnnotationDocument(placa, clasificacion) {
			url = "viewAnotacionesRMN.php?placa="+placa;
			document.getElementById('digitalDocument').src = url;
		}		

	</script>
  </head>
  <body onLoad="init()">
  <form name="documentos" id="documentos" target="digitalDocument" method="post" action="">
	<input type="hidden" name="txtPathFile" value="">
	<input type="hidden" name="idDocumento" value="">
	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
	  
	  <tr>
		<td colspan="2" bgcolor="#E1E1E1"><div align="center" class="Estilo1">DOCUMENTOS RELACIONADOS AL EXPEDIENTE <?php echo strtoupper($placa) ?></div></td>
	  </tr>
  
	  <tr>
	    <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
		<td width="25%" align="left" valign="top">
		<?php
			if($idDoc)  {
		?>
			&nbsp;&nbsp;<?php echo "<b>Resultado de B&uacute;squeda [".strtoupper($placa)."]:</b>"; ?>
			<hr size="1">
			<ul>
			<?php
					echo "<li><a href='javascript:' target='' title='View Digital Document' onclick='openDigitalDocument(\"{$busqueda[0]["nombre_pdf"]}\")'>[Img]</a>&nbsp;<a href='javascript:' target='' title='View Text Resume' onclick='openTxtDocument(\"{$busqueda[0]["id_documento"]}\")'>[Txt]</a>&nbsp;{$busqueda[0]["referencia"]}<br>";
			?>	
			</ul>	
			<p/>&nbsp;			
			<hr size="1" />

		<?php 
			}    
			
			$clasificacion = $expediente->selectClasificacionByPlaca($placa);
		?>
		

			&nbsp;&nbsp;<?php echo "<b>Informaci&oacute;n Catastral</b>"; ?>
			<hr size="1">
				<ul>
					<li><a href='javascript:' target='' title='View Cadastral Resume' onclick='openCadastralDocument("<?=$placa ?>","<?=$clasificacion?>")'>[HTML] Reporte Catastral</a>&nbsp;<br>	
				</ul>

			<?php 
				if($clasificacion == 'TITULO') {
			?>
			&nbsp;&nbsp;<?php echo "<b>Anotaciones Registro Minero</b>"; ?>
			<hr size="1">
				<ul>
					<li><a href='javascript:' target='' title='View Annotation List' onclick='openAnnotationDocument("<?=$placa ?>")'>[HTML] Anotaciones</a>&nbsp;<br>	
				</ul>
			<?php 
				} 
			?>	
				
			&nbsp;&nbsp;<?php echo "<b>E-Book del expediente ".strtoupper($placa)."</b>"; ?>
			<hr size="1">
			<ul>
			<?php
				if(!empty($listaMenu))
				foreach($listaMenu as $itemLista) {
					echo "<li><a href='javascript:' target='' title='View Digital Document' onclick='openDigitalDocument(\"{$itemLista["nombre_pdf"]}\")'>[Img]</a>&nbsp;<a href='javascript:' target='' title='View Text Resume' onclick='openTxtDocument(\"{$itemLista["id_documento"]}\")'>[Txt]</a>&nbsp;".utf8_decode($itemLista["referencia"])."<br>";
				}	
			?>	
			</ul>
		</td>
	    <td width="800" valign="top" align="left">
			<iframe name="digitalDocument" id="digitalDocument" frameborder="0" scrolling="auto" src="../../CMQ_Pruebas/IDB/DocumentosElectronicos/r2d2DocumentManagement.pdf" height="780" width="1000" align="top"></iframe>
	    </td>
	  </tr>
	  <tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2"><hr size="1" /></td>
	  </tr>
	  <tr>
		<td colspan="2">&nbsp;</td>
	  </tr>
	</table>
	<?php 
		if($idDoc) {
	?>
		<script>
				openTxtDocument("<?php echo $busqueda[0]["id_documento"]; ?>");
		</script>
	<?php
		}
	?>
</form>
</body>
</html>