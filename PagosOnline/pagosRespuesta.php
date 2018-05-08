<?php
	session_start();
	
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');	
	
	require_once("../Acceso/Config.php");
	require_once("../Modelos/PagosServicios.php");	
	
	function procesarDecimales($numero) {
		$decimales	= explode(".", $numero);
		// Primer decimal par y el segundo 5
		$primerDecimal 	= substr($decimales[1],0,1);
		$segundoDecimal = substr($decimales[1],1,1);
		
		echo "primer decimal vale: $primerDecimal y segundo decimal vale: $segundoDecimal";
		// Primer decimal impar y el segundo 5
		
		// Otros casos
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SIGMIN :: Pagos Online</title>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}

.style2 {
	text-align: center;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FFFFFF;
}

.style3 {font-size: 14px; font-family: Verdana, Arial, Helvetica, sans-serif;}
-->
</style>
</head>
<body>
<?php
	$ApiKey = $GLOBALS["Api_Key"];
	$merchant_id = $_REQUEST['merchantId'];
	$referenceCode = $_REQUEST['referenceCode'];
	$TX_VALUE = $_REQUEST['TX_VALUE'];
	$New_value = number_format($TX_VALUE, 1, '.', '');
	$currency = $_REQUEST['currency'];
	$transactionState = $_REQUEST['transactionState'];
	$firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
	$firmacreada = md5($firma_cadena);
	$firma = $_REQUEST['signature'];
	$reference_pol = $_REQUEST['reference_pol'];
	$cus = $_REQUEST['cus'];
	$extra1 = $_REQUEST['description'];
	$pseBank = $_REQUEST['pseBank'];
	$lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
	$transactionId = $_REQUEST['transactionId'];

	if ($_REQUEST['transactionState'] == 4 ) {
		$estadoTx = "Transacción aprobada";
	}

	else if ($_REQUEST['transactionState'] == 6 ) {
		$estadoTx = "Transacción rechazada";
	}

	else if ($_REQUEST['transactionState'] == 104 ) {
		$estadoTx = "Error en la Transacción";
	}

	else if ($_REQUEST['transactionState'] == 7 ) {
		$estadoTx = "Transacción pendiente";
	}

	else {
		$estadoTx=$_REQUEST['mensaje'];
	}


	if (strtoupper($firma) == strtoupper($firmacreada)) {
	?>
		
		<table align="center" width="700" border="0" cellspacing=5 cellpadding=5>
		<tr>
		<td colspan="2" align="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../Imgs/SIGMIN_Respuesta.jpg" width="303" height="78" /></td>
		</tr>		
		<tr>
		  <td colspan="2" bgcolor="#004030"><span class="style1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Detalles de la Orden</span> </td>
		  </tr>
		<tr  bgcolor="#F2F2F2">
		<td class="style3">Estado de la transaccion</td>
		<td ><?=strtoupper($estadoTx); ?></td>
		</tr>
		<tr>
		<td class="style3">Fecha de la transacci&oacute;n</td>
		<td><?php echo @$_REQUEST["processingDate"]; ?></td>
		</tr>
		<tr  bgcolor="#F2F2F2">
		<td class="style3">ID de la transaccion</td>
		<td><?php echo $transactionId; ?></td>
		</tr>
		<tr>
		<td class="style3">Referencia de la venta</td>
		<td><?php echo $reference_pol; ?></td> 
		</tr>
		<tr  bgcolor="#F2F2F2">
		<td class="style3">Referencia de la transaccion</td>
		<td><?php echo $referenceCode; ?></td>
		</tr>
		<tr>
		  <td class="style3">CUS </td>
		  <td><?php echo $cus; ?> </td>
			</tr>
			<tr  bgcolor="#F2F2F2">
			<td class="style3">Banco </td>
			<td><?php echo $pseBank; ?> </td>
			</tr>
		<tr>
		<td class="style3">Valor total</td>
		<td>$<?php echo number_format($TX_VALUE); ?></td>
		</tr>
		<tr  bgcolor="#F2F2F2">
		<td class="style3">Moneda</td>
		<td><?php echo $currency; ?></td>
		</tr>
		<tr>
		<td class="style3">Descripción</td>
		<td><?php echo ($extra1); ?></td>
		</tr>
		<tr  bgcolor="#F2F2F2">
		<td class="style3">Entidad</td>
		<td><?php echo ($lapPaymentMethod); ?></td>
		</tr>		
		<tr>
			<td colspan="2" bgcolor="#2F2E2E" align="center">
				<span class="style2">
					Sigmin S.A.S. - Propiedades Mineras-Todos los derechos reservados-2012.<br/>
					Cra 43 A # 1 Sur - 188 Of 507 Torre Empresarial Davivienda<br/>
					Medellín - Colombia<br/>
					contactenos@sigmin.com.co		  
				</span> 
			</td>
		  </tr>
		</table>
<?php
	}
	else
	{
?>
	<h1>Error validando firma digital.</h1>
<?php
	}
?>
</body>
</html>
