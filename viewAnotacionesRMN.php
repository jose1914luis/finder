<?php
	require_once("Acceso/Config.php");
	require_once("Modelos/AnotacionesRMN.php");	
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios_SGM.php");

	$anotacion 	= new AnotacionesRMN();
	$placa		= $_GET["placa"];
	
	// Procesamiento de expedientes, que pueden ser titulos o solicitudes	
	$listaAnotaciones = $anotacion->selectByPlaca($_GET["placa"]);

?>

<table width="860" border="0" align="center" cellpadding="0" cellspacing="5" class="table">
  
  <tr>
    <td colspan="6" bgcolor="#ededed"><div align="center" class="Estilo1">ANOTACIONES INSCRITAS EN EL REGISTRO MINERO</div></td>
  </tr>
  <tr>
    <td width="64" bgcolor="#F2EDE1"><div align="center"><strong>No.</strong></div></td>
    <td width="149" bgcolor="#F2EDE1"><div align="center"><strong>Fecha Anotaci&oacute;n </strong></div></td>
    <td width="120" bgcolor="#F2EDE1"><div align="center"><strong>Fecha Ejecutoria </strong></div></td>
    <td width="177" bgcolor="#F2EDE1"><div align="center"><strong>Tipo Anotaci&oacute;n </strong></div></td>
    <td colspan="2" bgcolor="#F2EDE1"><div align="center"><strong>Observaci&oacute;n</strong></div></td>
  </tr>
<?php
	$nroAnotacion = 1;
	if(!empty($listaAnotaciones)) {
	foreach($listaAnotaciones as $cadaAnotacion) {
?>
  <tr>
    <td align="center"><?php echo $nroAnotacion++; ?></td>
    <td align="center"><?php echo $cadaAnotacion["fecha_anotacion"]; ?>&nbsp;</td>
    <td align="center"><?php  echo $cadaAnotacion["fecha_ejecutoria"]; ?>&nbsp;</td>
    <td align="center"><?php  echo ($cadaAnotacion["tipo_anotacion"]); ?>&nbsp;</td>
    <td width="131" colspan="2"><?php  echo ($cadaAnotacion["observacion"]); ?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
<?php
	} } else {
?>
		<tr><td colspan='6'><center><h2>No hay anotaciones asociadas en SIGMIN</h2></center><hr></td></tr>
<?php
	}
?>

</table>
