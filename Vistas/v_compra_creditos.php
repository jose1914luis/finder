<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');	
	
	//require_once("../Acceso/Config.php");
	require_once("Modelos/PagosServicios.php");

	if(@$_SESSION['my_session'] != session_id()) { echo "<script>alert('Error en acceso a validador de firma');</script>"; }	
	else {
		
		// validación del POST
		$_POST 				= val_input($_POST);
		
		// obtencion de datos del formulario:
		$datosUsr			= $validate->getCaracterizacionByIdUsr($_SESSION['id_usuario']);

		$merchantId			= $GLOBALS ["Comercio"];
		$ApiKey				= $GLOBALS ["Api_Key"];		
		$amount				= floor($_POST["txtCompraCreditos"]/1000)*1000;
		$currency			= $GLOBALS ["currency"];
		$referenceCode		= time();
		$signature			= md5("$ApiKey~$merchantId~$referenceCode~$amount~$currency");	
		

		$accountId			= $GLOBALS ["Pais"]; 
		$tax			 	= $GLOBALS ["tax"];
		$taxReturnBase		= $GLOBALS ["taxReturnBase"];
		$test			 	= $GLOBALS ["test"];
		$buyerEmail			= $GLOBALS ["buyerEmail"];
		$urlRespuesta		= $GLOBALS ["urlRespuesta"];
		$urlConfirma		= $GLOBALS ["urlConfirma"];

		$form = new PagosServicios();
		
		$regConsignacion["varReferenceCode"] 	= $referenceCode;
		$regConsignacion["varTipoServicio"] 	= $GLOBALS ["IdTipoServicio"];
		$regConsignacion["varAmount"]			= $amount;
		$regConsignacion["varDocumento"]        = $datosUsr["numero_documento"];
		$regConsignacion["varNombre"]           = ($datosUsr["nombres"]!="")? $datosUsr["nombres"] : $datosUsr["razon_social"];
		$regConsignacion["varApellido"]         = $datosUsr["apellidos"];
		$regConsignacion["varEmail"]            = $datosUsr["correo_electronico"];
		$regConsignacion["varTelefono"]         = $datosUsr["telefono"];
		$regConsignacion["varDireccion"]        = $datosUsr["direccion"];
		$regConsignacion["varTipoDocumento"]    = $datosUsr["id_tipo_documento"];
		$regConsignacion["varMunicipio"]        = $datosUsr["id_municipio"];
		
		$resp = $form->insertarConsignacion($regConsignacion);
	}
	

?>

<!DOCTYPE html>
<html>
<head></head>
<body>

	<form name="frmPagoCreditos" method="post"    action="<?=$GLOBALS["POST"]?>">
		<input name="merchantId"    type="hidden" value="<?=$merchantId?>"   >
		<input name="accountId"     type="hidden" value="<?=$accountId?>" >
		<input name="description"   type="hidden" value="<?=$GLOBALS ["DescripcionServicio"]?>">
		<input name="referenceCode" type="hidden" value="<?=$referenceCode?>" >
		<input name="tax"           type="hidden" value="<?=$tax?>"  >
		<input name="taxReturnBase" type="hidden" value="<?=$taxReturnBase?>" >
		<input name="currency"      type="hidden" value="<?=$currency?>" >
		<input name="signature"     type="hidden" value="<?=@$signature?>"  >
		<input name="test"          type="hidden" value="<?=$test?>" >
		<input name="amount"		type="hidden" value="<?=$amount?>" > 
		<input name="txtDocumento"	type="hidden" value="<?=$datosUsr["numero_documento"]?>" > 
		<input name="txtNombre"		type="hidden" value="<?=$regConsignacion["varNombre"]?>" > 
		<input name="txtApellido"	type="hidden" value="<?=$datosUsr["apellidos"]?>" > 
		<input name="buyerEmail"	type="hidden" value="<?=$datosUsr["correo_electronico"]?>" > 
		<input name="txtTelefono"	type="hidden" value="<?=$datosUsr["telefono"]?>" > 
		<input name="selMunicipio"	type="hidden" value="<?=$datosUsr["id_municipio"]?>" > 
		<input name="txtDireccion"	type="hidden" value="<?=$datosUsr["direccion"]?>" > 
	</form>
	<div></div>	
	<script>
		document.frmPagoCreditos.submit();
	</script>	
			
</body>
</html>