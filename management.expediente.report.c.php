<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/DocumentManagement.php");
//	require_once("modelos/SeguimientosUsuarios.php");	
	//require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios_SGM.php");
	
	//validación de usuarios en CMQ
	$validate = new Usuarios_SGM();	
	$Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);	
	if(empty($Id_Empresa) || !$Id_Empresa) echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";
	
	$placa = "";
	if (isset($_GET["placa"]) && $_GET["placa"] != "") {
		$documento 	= new DocumentManagement();
		$idDoc 		= $_GET["idfrm"];
		$listaMenu 	= $documento->selectFormsByPlaca($_GET["placa"]);
		$placa 		= $_GET["placa"];
		
		if($idDoc) 	$busqueda = $documento->selectFormsByIdDocumento($_GET["idfrm"]);
	}
		
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>View Report :: SIGMIN</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>Generar Area Libre</title>

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
		function openPdfDocument(rutaArchivo) {
			document.forms[0].action = "management.expediente.report.pdf.c.php";
			document.forms[0].txtPathFile.value=rutaArchivo;
			document.forms[0].submit();
		}
		
		function openTxtDocument(idDocument) {
			document.forms[0].action = "management.expediente.report.txt.c.php";
			document.forms[0].idDocumento.value=idDocument;
			document.forms[0].submit();
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
					echo "<li><a href='javascript:' target='' title='View PDF Document' onclick='openPdfDocument(\"{$busqueda[0]["nombre_pdf"]}\")'>[Pdf]</a>&nbsp;<a href='javascript:' target='' title='View Text Resume' onclick='openTxtDocument(\"{$busqueda[0]["id_documento"]}\")'>[Txt]</a>&nbsp;{$busqueda[0]["formulario"]}<br>";
			?>	
			</ul>	
			<p/>&nbsp;			
			<hr size="1" />

		<?php 
			}    
		?>
		
		
			&nbsp;&nbsp;<?php echo "<b>E-Book del expediente ".strtoupper($placa).":</b>"; ?>
			<hr size="1">
			<ul>
			<?php
				foreach($listaMenu as $itemLista) {
					echo "<li><a href='javascript:' target='' title='View PDF Document' onclick='openPdfDocument(\"{$itemLista["nombre_pdf"]}\")'>[Pdf]</a>&nbsp;<a href='javascript:' target='' title='View Text Resume' onclick='openTxtDocument(\"{$itemLista["id_documento"]}\")'>[Txt]</a>&nbsp;{$itemLista["formulario"]}<br>";
				}	
			?>	
			</ul>
		</td>
	    <td width="719">
			<iframe name="digitalDocument" frameborder="0" scrolling="auto" src="../../CMQ_Pruebas/IDB/DocumentosElectronicos/r2d2DocumentManagement.pdf" height="800" width="950" align="top"></iframe>
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
