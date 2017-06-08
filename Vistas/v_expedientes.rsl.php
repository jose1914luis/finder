<?php

	require_once("Modelos/ExpedientesSGM.php");
	require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 	
	
	$expediente = new ExpedientesSGM();
	$exp 		= $expediente->selectExpedienteByPlaca($_GET["placa"]);
	
	if(!empty($exp)) {
		$generaURL		= new ControlPopups();
		$codAcceso 		= $generaURL->setControlPopup($_GET["placa"], $exp[0]["tipo_expediente"]);
		
		$url = "http://www.sigmin.co/finderaccount/reporteAreasAccount.php?cod_acceso=$codAcceso";	

?>		
	<iframe onload="javascript:resize()" src="<?=$url ?>" height="100%" width="100%" frameborder="0" scrolling="no" name="frm_catalogo" id="frm_catalogo"></iframe>
	<script>
		function resize() {
			height = $("#frm_catalogo").contents().find("body").outerHeight();
			$("#frm_catalogo").attr("height", height + "px");
		}
	</script>		
<?php		
		
	} else {
		echo "<center><h3>No existen registros asociados a la placa {$_POST["txtPlaca"]}</h3></center>";
	}
	
	
?>