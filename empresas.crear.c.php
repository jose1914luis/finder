<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Empresas.php");
	require_once("Modelos/Departamentos.php");

	// variables del controlador	
	$msgError = "";
	
	$empresa 	= new Empresas();	
	$deptos  	= new Departamentos();
	
	//$accionPage = new SeguimientosUsuarios;
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["emp_nit"])&&trim($_POST["emp_nit"])!="") {	
		$resultado = $empresa->insertAll($_POST);
		if($resultado != "OK")
			$msgError = "<script>alert('Error durante el proceso de creación de la Empresa {$_POST["emp_nombre"]}. $resultado')</script>";
		else
			$msgError = "<script>alert('La Empresa {$_POST["emp_nombre"]} a sido almacenada correctamente')</script>";
	}
	
	include("Vistas/empresas.crear.v.php");	

?>	
