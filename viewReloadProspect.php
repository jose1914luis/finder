<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	
	$prp 		= new ProspectosBogSGM(); 
	$prospects 	= $prp->selectProspectoByEmpresa($_POST["IdEmpresa"]); 
	
	if(!empty($prospects)) {
		echo '<option value="0">Prospectos';
		foreach( $prospects as $cadaProspecto)
		echo "<option value='{$cadaProspecto["placa"]}'>{$cadaProspecto["placa"]}";
	} else 
		echo '<option value="0">No hay Prospectos';
?>
