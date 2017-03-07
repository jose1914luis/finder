<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Variables/ArraysDatos.php");
	require_once("Modelos/Proyectos.php");

	// variables del controlador	
	$msgError = "";
	$Id_Empresa = 2;  // pendiente de volver variable global
	
	$pry 	= new Proyectos();	

	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["txtNombreProyecto"])&&trim($_POST["txtNombreProyecto"])!="") {	
		$resultado = $pry->insertAll($_POST, $Id_Empresa);
		if($resultado != "OK")
			$msgError = "<script>alert('Error durante el proceso de creación del proyecto {$_POST["txtNombreProyecto"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('El Proyecto {$_POST["txtNombreProyecto"]} ha sido creado correctamente')</script>";
	} 
	
	include("Vistas/proyectos.crear.v.php");	

?>
