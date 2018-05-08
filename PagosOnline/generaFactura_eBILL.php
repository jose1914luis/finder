<?php

	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');	
	
	require_once("../Acceso/Config.php");
	require_once("../Modelos/PagosServicios.php");	
	require_once("../Modelos/FacturaEbill.php");	
	require_once("../Utilidades/LibCurl.php");	
	
	function digito_verificacion($numeroDoc, $nroDigitos=9) {
		$num 		= array(3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71);
		$numeroDoc  = substr(trim($numeroDoc),0, $nroDigitos);
		$ultimaPos 	= strlen($numeroDoc)-1;
		$suma		= 0;
		
		for($i=0; $i < strlen($numeroDoc); $i++) {

			$numeroAnalisis = substr($numeroDoc, $ultimaPos,1)*1;                       
			$suma += $num[$i]*$numeroAnalisis;       
			$ultimaPos -= 1;       	
			
			echo "<h1>{$num[$i]} * $numeroAnalisis";

		}
		
		$digitoVerificacion = $suma%11;
		
		 if($digitoVerificacion > 1)
			$digitoVerificacion = 11 - $digitoVerificacion;
			
		return($digitoVerificacion);	
	}

	
	function liquidacionEbill($nroTransaccion) {
		$pagos = new PagosServicios;	

		$infoPago = $pagos->getConsignacionByNroTransaccion($nroTransaccion);
		$infoEbill = $pagos->getConsignacionToEbillPay($nroTransaccion);	
		
		//--------------------------------------------------------------------------------------
		// Código suministrado por eBILL:
		$FacturaEbill			= new FacturaEbill();
		
		if($infoEbill["id_tipo_documento"]==5) {
			if(strlen($infoEbill["numero_documento"])==10)
				$digitoVerifica		= substr($infoEbill["numero_documento"], -1);
			else if($infoEbill["numero_documento"]==9)
				$digitoVerifica		= digito_verificacion($infoEbill["numero_documento"]);
			else
				$digitoVerifica		= "";
		} else 
			$digitoVerifica		= "";
		
		// Incializacion de variables
		//array FacCab
		// Asignaciones

		$AnoAprobacionRes				= $GLOBALS ["anio_aprob_res"];
		$CiudadDANE						= $infoEbill["mpio_dane"];
		$CorreoCliente					= $infoEbill["correo_electronico"];  // pruebas: "colombiamineria@gmail.com";
		$DescripcionPlan				= "";
		$Descuento						= "0";
		$DigitoVerificaCliente			= $digitoVerifica;
		$DireccionCliente				= $infoEbill["direccion"];
		$FechaExpedicion				= $infoEbill["fecha_proceso"];
		$FechaFinalPeriodoFacturado		= $infoEbill["fecha_proceso"];
		$FechaInicialPeriodoFacturado	= $infoEbill["fecha_proceso"];
		$FechaVencimiento				= $infoEbill["fecha_proceso"];
		$FormaPago						= "Online Contado";
		$IDInterno						= $GLOBALS ["nit_empresa"];	// ??????????
		$IdPlan							= "";
		$InfoAdicional					= "";
		$NitCliente						= $infoEbill["numero_documento"];
		$NitEmpresa						= $GLOBALS ["nit_empresa"]; 
		$NombreCliente					= $infoEbill["nombres"];
		$NombrePlan="";
		$NumAprobacionRes				= $GLOBALS ["resol_fact"]; //"1234567";
		$NumFactura						= "2030"; // ?????????????
		$OrderID						= $infoEbill["numero_transaccion"];
		$Prefijo						= $GLOBALS ["prefijo"];
		$SubTotal						= $infoEbill["valor_parcial"];
		$TelefonoCliente				= $infoEbill["telefono"];
		$TipoIDCliente					= $infoEbill["id_tipo_documento"];
		$Total							= $infoEbill["valor"];
		$TotalIVA						= $infoEbill["valor_iva"];

		$FacCab=array(
			'AnoAprobacionRes'=>$AnoAprobacionRes,
			'CiudadDANE'=>$CiudadDANE,
			'CorreoCliente'=>$CorreoCliente,
			'DescripcionPlan'=>$DescripcionPlan,
			'Descuento'=>$Descuento,
			'DigitoVerificaCliente'=>$DigitoVerificaCliente,
			'DireccionCliente'=>$DireccionCliente,
			'FechaExpedicion'=>$FechaExpedicion,
			'FechaFinalPeriodoFacturado'=>$FechaFinalPeriodoFacturado,
			'FechaInicialPeriodoFacturado'=>$FechaInicialPeriodoFacturado,
			'FechaVencimiento'=>$FechaVencimiento,
			'FormaPago'=>$FormaPago,
			'IDInterno'=>$IDInterno,
			'IdPlan'=>$IdPlan,
			'InfoAdicional'=>$InfoAdicional,
			'NitCliente'=>$NitCliente,
			'NitEmpresa'=>$NitEmpresa,
			'NombreCliente'=>$NombreCliente,
			'NombrePlan'=>$NombrePlan,
			'NumAprobacionRes'=>$NumAprobacionRes,
			'NumFactura'=>$NumFactura,
			'OrderID'=>$OrderID,
			'Prefijo'=>$Prefijo,
			'SubTotal'=>$SubTotal,
			'TelefonoCliente'=>$TelefonoCliente,
			'TipoIDCliente'=>$TipoIDCliente,
			'Total'=>$Total,
			'TotalIVA'=>$TotalIVA
		);

		/*
		echo "<h3>FacCab</h3>";
		echo "<pre>";
		print_r($FacCab); // or var_dump()
		echo "</pre><br>";
		*/
		/****************************************************/

		//array DetFac
		// Asignaciones 

		$Cantidad					= "1";
		$CodigoProducto				= $infoEbill["codigo"];
		$Codigos 					= array(); //array('1234567890','0987654321'); ????????
		$DescripcionConcepto		= "";
		$DescripcionProducto		= $infoEbill["servicio"];
		$DescuentoConcepto			= "0";
		$IVAProducto				= $infoEbill["valor_iva"];
		$PorIVAProducto				= $GLOBALS ["ebill_iva"];
		$ValorUnitario				= $infoEbill["valor"];

		$DetFac = array('CFDDetConexion'=>array(
			'Cantidad'=>$Cantidad
			,'CodigoProducto'=>$CodigoProducto
			,'Codigos' => $Codigos
			,'DescripcionConcepto'=>$DescripcionConcepto
			,'DescripcionProducto'=>$DescripcionProducto
			,'DescuentoConcepto'=>$DescuentoConcepto
			,'IVAProducto'=>$IVAProducto
			,'PorIVAProducto'=>$PorIVAProducto
			,'ValorUnitario'=>$ValorUnitario
		));


		/*
		echo "<h3>DetFac</h3>";
		echo "<pre>";
		print_r($DetFac); // or var_dump()
		echo "</pre><br>";
		*/

		/*********************************/
		// Opciones
		// Asignaciones
		//0 False
		//1 True                 
		$EmisionAutomatica				= $GLOBALS ["ebill_enviomail"]; // 1: envío de factura de parte de ebill    
		$FacturasSinAcuerdo				= 1; // ?????????????????  El valor debe ser 0, si tiene acuerdo, para prod se dejará en 1       
		$NoTieneAcuerdosFisicos			= 1; // ?????????????????        
		$OrderControl					= $GLOBALS ["ebill_control"]; // 1: control de no repeticion de factura       
		$TokenConexion					= $GLOBALS ["ebill_token"]; //'E7E3CF5B-7AB2-4EB6-ACB6-ACD2650CE443';     
		$eBillAdmNumerosFactura			= 1;  // 1: utiliza el número de consecutivo de ebill para generar factura 

		$Opciones = array(
			'EmisionAutomatica'=>$EmisionAutomatica
			,'FacturasSinAcuerdo'=>$FacturasSinAcuerdo
			,'NoTieneAcuerdosFisicos'=>$NoTieneAcuerdosFisicos
			,'OrderControl'=>$OrderControl
			,'TokenConexion'=>$TokenConexion
			,'eBillAdmNumerosFactura'=>$eBillAdmNumerosFactura  
		);

		/*
		echo "<h3>Opciones</h3>";
		echo "<pre>";
		print_r($Opciones); // or var_dump()
		echo "</pre><br>";
		*/

		/* asignacion y llamado Principal */

		$CargarFacturasToeBillGeneric=array(
			'FacCab'=>$FacCab
			,'DetFac'=>$DetFac
			,'Opciones'=>$Opciones
		);


		//llamado
		$resultado=$FacturaEbill->consumir('CargarFacturasToeBillGeneric', $CargarFacturasToeBillGeneric);

		$respuesta = $resultado->CargarFacturasToeBillGenericResult;
		echo ("<b>$respuesta</b>");
		
		if(strpos($respuesta,"777.")!==false)
			$nroFactEbill = substr($respuesta, strpos($respuesta,"[")+1, strpos($respuesta,"]")-strpos($respuesta,"[")-1) ;
		else
			$nroFactEbill = "N/A";
		// Instruccion para monitorear operaciones de facturación luego de confirmar el pago
		$saveFactura = $pagos->monitorearFacturaEbill($infoEbill["numero_transaccion"], $nroFactEbill, $respuesta);
		

		echo "<h3>Peticion CargarFacturasToeBillGeneric</h3>";
		echo "<pre>";
		print_r($CargarFacturasToeBillGeneric); 
		echo "</pre><br>";	
	}

	//liquidacionEbill("1459294958");
	//liquidacionEbill("1459557315");
	//liquidacionEbill("1461082071");
	
?>
