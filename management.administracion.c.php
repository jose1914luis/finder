<?php
	session_start();
	require_once("Acceso/Config.php");
	require_once("viewValidaQuery.php");
	require_once("viewDocuments.php");
	require_once("Modelos/IndexacionesQueries.php");	
	require_once("Modelos/DocumentManagement.php");	
	//require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios_SGM.php");
	
	
	//validación de usuarios en CMQ
	$validate = new Usuarios_SGM();	
	$Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);	
	if(empty($Id_Empresa) || !$Id_Empresa) echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";

	// variables del controlador	
	$msgError 	= "";

?>
<html>
	<head>
		<title>:: SIGMIN - Document Management ::</title>
		<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
		<script type="text/javascript" src="Utilidades/jquery.min.js"></script>
	    <style type="text/css">
			<!--
			.Estilo1 {
				color: #FFFFFF;
				font-weight: bold;
			}
			-->
        </style>
		<script>
			// Definición de variables locales y globales de la página
			function consultarIndexamiento()	{
				strQuery = document.forms[0].searchQuery.value.replace(/\s/g,"%20");
				 $("#searchContenido").load('viewDocuments.php?query=' + strQuery);
			};		
		</script>			
</head>
	<body>
	<form name="form1" method="post" action="">
	  <table width="100%" border="0">
        <tr>
          <td bgcolor="#993300"><div align="center" class="Estilo1">:: SIGMIN - Document Management :: </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search: 
            <input name="searchQuery" type="text" size="65">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="Submit" value=" &lt;O&gt;" onClick="consultarIndexamiento()">		   </td>
        </tr>
        <tr>
          <td><hr size="1"></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Advanced Search by Date: </td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Type: 
            <select name="selTipoFecha">
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Initial Date:&nbsp;
            <input type="text" name="txtFechaInicial">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End Date:&nbsp;			
            <input type="text" name="txtFechaFinal">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="Submit2" value=" &lt;O&gt;" onClick="consultarIndexamientoByFecha()"></td>
        </tr>
        <tr>
          <td><hr size=1></td>
        </tr>
        <tr>
          <td>
		  <div id="searchContenido"></div>		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </form>
	<p>&nbsp;</p>
	</body>
</html>