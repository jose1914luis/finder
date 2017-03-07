<?php

	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios_SGM.php");
	require_once("/home/sigmin/public_html_services/Modelos/ServiciosSigmin.php");

	// variables del controlador	
	$msgError = "";
	$usuario 	= new Usuarios_SGM();	
	$resp 		= $usuario->habilitaUsrTmp($_GET);
	
	if($resp=='OK')  {
		$meses  		= 1;  // mes de la promocion
		$fechaInicia	= date('Y-m-j');
		$fechaVence 	= strtotime ("+$meses month" , strtotime ( $fechaInicia ));
		$fechaVence 	= date ( 'Y-m-j' , $fechaVence );
		
		//$promo = new ServiciosSigmin();
		//$resp = $promo->habilitarPromocion($_GET["email"],1,$fechaInicia,$fechaVence);	
		header("location: http://www.sigmin.co/finder/");		
	}

	else 
		header("location: http://www.sigmin.co/finder/pageError.php?error=");
?>

