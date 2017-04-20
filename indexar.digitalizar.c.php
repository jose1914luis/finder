<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Empresas.php");
	require_once("Modelos/Plantillas.php");
	require_once("Modelos/DocumentosPlantillas.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	
	
	// Creación de objetos para menus
	$template 			= new Plantillas();
	$listaPlantillas 	= $template->selectPlantillasAll();
	
	$empresa 			= new Empresas();
	$listaEmpresas		= $empresa->selectIdNameAll();

	
	$paginaInclude = "Vistas/indexar.digitalizar.v.php";	
	if(isset($_POST["operacionForm"])) {
		if($_POST["operacionForm"] == "indexar.digitalizar") $paginaInclude = "Vistas/indexar.digitalizar.v.php";
	}	

	
	 // El Id de empresa se obtiene en la selección de empresas y se mantiene hasta que el usuario lo cambie
	$idEmpresa = $_SESSION["idEmpresa"]; 

	$msgError = "";	
	$fechaHora = @date("d-m-Y H:i:s");
	$nombreImagen = session_id().md5($fechaHora).".pdf";
	$carpetaImagenes = "DocumentosElectronicos/";
	
	
	//$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);		
	
	if(isset($_POST["codigoExpediente"])&&$_POST["codigoExpediente"]!="") {	
		$docPlantillas = new DocumentosPlantillas();
		$rutaArchivo = $template->getRutaArchivoPlantilla($_POST["idPlantilla"]);
		
/*
		echo "<hr>";
		print_r($_POST);
		echo "<hr>".$docPlantillas->procesarRequerimientos($_POST);
		echo "<hr>".$docPlantillas->procesarRespuestas($_POST);
		echo "<hr>";
		print_r($_FILES);
*/		
		$docPlantillas = new DocumentosPlantillas();
		$operacion = $docPlantillas->insertAll($_POST, session_id(), $rutaArchivo);
		if( $operacion == 'OK') {
			//acciones con almacenamiento de cada indice
			$msgError 	= "<script>alert('Indexamiento almacenado correctamente'); </script>";

			$accionPage = new SeguimientosUsuarios;
			$accionPage->generarAccion("Indexamiento de documento - Radicado # {$_POST["nroRadicado"]}");
			
		} else
			$msgError = "<script>alert('".$operacion."'); </script>";

	}  
?>

<html>
	<head>
		<!--<meta charset="utf-8"/> -->
		<!-- <script language="javascript" type="text/javascript" src="Utilidades/jquery-1.9.1.js"></script> --> 
		<script type="text/javascript" src="Utilidades/jqueryUpload/jquery.js"></script> 
		<script type="text/javascript" src="Utilidades/jqueryUpload/ajaxfileupload.js"></script>			
		<script>
			// variables globales
			nroImagen 		= 1;  // Para anexo de imagenes
			nroPagina 		= 0;  // Paginación de imágenes, cada página contiene 5 imágenes
			listaImagenes 	= [];
			
			function obtenerAncho() {
				return ($(document).width()-420);
			}

			function ajaxFileUpload()
			{
				//starting setting some animation when the ajax starts and completes
				$("#loading")
				.ajaxStart(function(){
					$(this).show();
				})
				.ajaxComplete(function(){
					$(this).hide();
				});
				
				/*
					prepareing ajax file upload
					url: the url of script file handling the uploaded files
								fileElementId: the file type of input element id and it will be the index of  $_FILES Array()
					dataType: it support json, xml
					secureuri:use secure protocol
					success: call back function when the ajax complete
					error: callback function when the ajax failed
					
				*/
				$.ajaxFileUpload
				(
					{
						url:'loadDigitalPage.php', 
						secureuri:false,
						fileElementId:'fileToUpload',
						dataType: 'json',
						success: function (data, status)
						{
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}else
								{
									//alert(data.msg);
									//$("#respuesta").html(data.msg);
									newImagen(data.urlImagen);
								}
							}
						},
						error: function (data, status, e)
						{
							alert(e);
						}
					}
				)
				
				return false;

			} 
		</script>
		<title>:: SIGMIN :: Document Management</title>
	</head>
	<body>
		<table align="center" border="0" >
			<tr>
				<td colspan="2" align="center">
					<?php include("Vistas/document.header.v.php"); ?>
				</td>
			</tr>
			<tr>
				<!-- Area asignada para la definición del menu de seleccion -->
				<td width="260" valign="top"> 
					<?php include("Vistas/document.menu.v.php"); ?>					
				</td>
				<script>document.write("<td valign='top' width='" + obtenerAncho() + "'>");</script>				
				<!-- Inicio Contenido de indexamiento de documentos -->					
					<table align='center' border='0' width='95%' cellspacing='5'>
						<tr>
							<td>  
								<?php
									include($paginaInclude);
								?>
							</td>
						</tr>
					</table>
				<!-- Fin Contenido de indexamiento de documentos -->	
				<div id="eraseImage"></div>				
				</td>
			</tr>
		</table>
		<?php
			if(!empty($msgError)) echo $msgError;
		?>
	</body>
</html>