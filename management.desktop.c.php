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
		$listaMenu 	= $documento->selectFormsByPlaca($_GET["placa"]);
		$placa 		= $_GET["placa"];
	}
		
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Document Management :: SIGMIN</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
	<meta name="apple-mobile-web-app-capable" content="yes">


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
	#bg {
		position: fixed;
		z-index: -1;
		top: 0;
		left: 0;
		width: 100%;
	}
	</style>
	<script type="text/javascript" src="Utilidades/jquery.min.js"></script>	
	<script type="text/javascript">
	function updateBackground() {
	  screenWidth = $(window).width();
	  screenHeight = $(window).height();
	  bg = $("#bg");
	  
	  // Proporcion horizontal/vertical
	  ratio = 1;
	  
	  if (screenWidth/screenHeight > ratio) {
		$(bg).height("100%");
		$(bg).width("100%");
	  } else {
		$(bg).width("auto");
		$(bg).height("100%");
	  }
	
	  if ($(bg).width() > 0) {
		$(bg).css('left', (screenWidth - $(bg).width()) / 2);
	  }  
	}
	$(document).ready(function() {
	  updateBackground();
	  $(window).bind("resize", function() {
		updateBackground();
	  });
	});
	</script>	
	<script type="text/javascript">
		// variables globales
		var winP=null, resultados=null;
	
		function searchOption() {
			lineaHTML = '<p>&nbsp;<p>&nbsp;<p>&nbsp;<form method="post" action="">'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Search:</b> '+
					'<input name="searchQuery" type="text" size="65">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
					'<input type="button" name="Submit" value=" &lt;O&gt;" onClick="consultarIndexamiento()">'+		
					'<input type="button" name="close" value=" Close" onClick="clearDIV()">'+												
				'</form>'+
				'<div id="searchContenido"></div>';
			$('#despliegueTexto').html(lineaHTML);			
		}
		
		function searchChronology() {
			lineaHTML = '<p>&nbsp;<p>&nbsp;<p>&nbsp;<form method="post" action="">'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Chronology :: Aplication/Title Code:</b> '+
					'<input name="searchPlaca" type="text" size="65">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
					'<input type="button" name="Submit" value=" &lt;O&gt;" onClick="consultarCronologia()">'+		
					'<input type="button" name="close" value=" Close" onClick="clearDIV()">'+												
				'</form>'+
				'<div id="searchContenido"></div>';
			$('#despliegueTexto').html(lineaHTML);			
		}
		
		function searchAlert() {
			lineaHTML = '<p>&nbsp;<p>&nbsp;<p>&nbsp;<form method="post" action="">'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Alerts :: Aplication/Title Code:</b> '+
					'<input name="searchPlaca" type="text" size="65">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
					'<input type="button" name="Submit" value=" &lt;O&gt;" onClick="consultarAlerta()">'+		
					'<input type="button" name="close" value=" Close" onClick="clearDIV()">'+												
				'</form>'+
				'<div id="searchContenido"></div>';
			$('#despliegueTexto').html(lineaHTML);			
		}		
		
		function clearDIV() {
			$('#despliegueTexto').html('');
		}
		
		function consultarIndexamiento() { 
			strQuery = document.forms[0].searchQuery.value.replace(/\s/g,"%20");
			if(strQuery=="")  
				return 0;
			$.post('viewDocuments.php', { query : strQuery }, function(resp) {
					if(resp!="")  {	
						if(resultados != null) resultados.close();
						resultados = window.open("", "Ventana", "width=700 height=450 scrollbars=yes");
						resultados.document.write(resp);
						resultados.document.title = ":: SIGMIN - Results"; 		
						resultados.focus();							
					} else
						alert("Search Index: Data not found !!!");
				});				
		}
		
		function consultarCronologia() {
			strQuery = document.forms[0].searchPlaca.value.replace(/\s/g,"%20");
			if(strQuery=="")  
				return 0;
			$.post('management.expediente.chronology.php', { placa : strQuery }, function(resp) {
					if(resp!="")  {	
						if(resultados != null) resultados.close();
						resultados = window.open("", "Ventana", "width=700 height=450 scrollbars=yes");
						resultados.document.write(resp);
						resultados.document.title = ":: SIGMIN - Results"; 		
						resultados.focus();							
					} else
						alert("Chronology : Data not found !!!");
				});				
		}
		
		function consultarAlerta() {
			strQuery = document.forms[0].searchPlaca.value.replace(/\s/g,"%20");
			$.post('management.expediente.alerts.php', { placa : strQuery }, function(resp) {
					if(resp!="")  {	
						if(resultados != null) resultados.close();
						resultados = window.open("", "Ventana", "width=700 height=450 scrollbars=yes");
						resultados.document.write(resp);
						resultados.document.title = ":: SIGMIN - Results"; 		
						resultados.focus();							
					} else
						alert("Alerts : Data not found !!!");
				});				
		}	
		
		function consultarExpedientes() { 
			Id_Empresa = <?php echo $Id_Empresa; ?>;
			if(Id_Empresa=="")  
				return 0;
			$.post('viewExpedientesEmpresas.php', { idEmpresa : Id_Empresa }, function(resp) {
					if(resp!="")  {	
						if(resultados != null) resultados.close();
						resultados = window.open("", "Ventana", "width=700 height=450 scrollbars=yes");
						resultados.document.write(resp);
						resultados.document.title = ":: SIGMIN - Results"; 		
						resultados.focus();							
					} else
						alert("Search Document: Data not found !!!");
				});				
		}		
		
	</script>	

  </head>
  <body>
	<img src="Imagenes/fondoDesktopDM.jpg" id="bg" />  
    <table width="100%" border="0" align="center">
      <tr>
        <td width="7%" height="50">&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3" rowspan="9" valign="top">
			<div id="despliegueTexto"></div>			
        </td>
      </tr>
      
      <tr>
        <td height="82"><div align="center"><a href="javascript:" onClick="searchOption()"><img src="Imagenes/search.gif" width="87" height="82" border="0" title="Search by keyword"></a><br>
        </div></td>
      </tr>
      <tr>
        <td height="10">&nbsp;</td>
      </tr>
      <tr>
        <td height="82"><div align="center"><a href="javascript:" onClick="consultarExpedientes()"><img src="Imagenes/documents.gif" width="87" height="82" border="0"  title="Documents Folder"></a></div></td>
      </tr>
      <tr>
        <td height="10">&nbsp;</td>
      </tr>
      <tr>
        <td height="82"><div align="center"><a href="javascript:" onClick="searchChronology()"><img src="Imagenes/cronologia.gif" width="87" height="82" border="0" title="Search by Chronology"></a></div></td>
      </tr>
      <tr>
        <td height="10">&nbsp;</td>
      </tr>
      <tr>
        <td height="82"><div align="center"><a href="javascript:" onClick="searchAlert()"><img src="Imagenes/alerta_dm.gif" width="87" height="82" title="Search by Alerts"></a></div></td>
      </tr>
      <tr>
        <td>
					
		</td>
      </tr>
    </table>

</body>
</html>
