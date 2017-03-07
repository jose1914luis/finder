<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');	
	
	require_once("../Acceso/Config.php");
	require_once("../Modelos/PagosServicios.php");	
	require_once("../Utilidades/LibCurl.php");	
	require_once("generaFactura_eBILL.php");
	require_once("../Modelos/CreditosUsuarios.php");	

	$_REQUEST 		= val_input($_REQUEST);
	
	$mensaje 		= print_r($_REQUEST, true);
	$nombre_archi	= $_REQUEST['reference_sale'].".txt";
	
	$pagos 	= new PagosServicios();	
	$mensaje .= $pagos->insertPago($_REQUEST);

	$ApiKey 			= $GLOBALS["Api_Key"];

	$merchant_id 		= $_REQUEST['merchant_id'];
	$referenceCode 		= $_REQUEST['reference_sale'];
	$TX_VALUE 			= $_REQUEST['value'];
	$New_value 			= number_format($TX_VALUE, 1, '.', '');
	$currency 			= $_REQUEST['currency'];
	$transactionState 	= $_REQUEST['state_pol'];
	$firma_cadena 		= "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
	
	$firmacreada = md5($firma_cadena);
	$firma = $_REQUEST['sign'];
	
	
	if (strtoupper($firma) == strtoupper($firmacreada)) {
		if ($_REQUEST['state_pol'] == 4 ) {
			$estadoTx = "Transacción aprobada";		

			// Actualización de base de datos			
			$resp  = $pagos->habilitarConsignacion($_REQUEST['reference_sale'], $_REQUEST['value']);			
			$mensaje .= "\nHabilita Consignacion. \n".$resp;  
			
			
			if($resp=='OK') {
				$infoPago = $pagos->getConsignacionByNroTransaccion($_REQUEST['reference_sale']);
				
				// Aqui se asignan los creditos:
				$datos_usr = $pagos->getIdUsuarioByConsignacion($_REQUEST['reference_sale']);
				$cred = new CreditosUsuarios();
				$estado = $cred->comprarCreditos($datos_usr["id_usuario"], floor($datos_usr["precio"]/$GLOBALS["SIGCoin_pesos"]));				
				$mensaje .= "\nCreditos: \n".print_r($datos_usr,true)."\n estado transaccion: $estado \n ";
				
				// envio de correo de factura
				/*
					$url	= "http://www.sigmin.com.co/EmailServices/SendConfirmaPago.php";
					$params = 	array(
						'tipo_servicio' 		=> $infoPago["id_tipo_servicio"],
						'email' 				=> $infoPago["correo_electronico"],
						'nombre'				=> strtoupper($infoPago["nombres"]),
						'descripcion_servicio' 	=> $infoPago["servicio"],
						'codigo_acceso'	 		=> $infoPago["codigo_acceso"]
					);
										
					$connCurl	= new LibCurl;
					$resultado 	= $connCurl->curl_download($url, $params);									
					//$emailRs 	= json_decode($resultado, true);				
					$emailRs 	= $resultado;
				*/	
					
					// Aqui se genera la factura electrónica en eBILL
					liquidacionEbill($referenceCode);
					
					$mensaje .= "\nEnvia datos a email. \n".print_r($params,true)."\n infoPago: $infoPago \n "; //Repuesta Email: $emailRs";
			}
		} 				
	} else {
		// error en la firma		
	}

	// Almacenamiento del registro en archivo
	$fp 	= fopen("transacciones/$nombre_archi", 'a');		
	fwrite($fp, $mensaje);		
	fclose($fp);	

?>