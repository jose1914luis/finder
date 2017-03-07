<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Municipios.php");
	
	if (isset($_GET["idDepto"]) && $_GET["idDepto"] != "") {
		
		$mpiosView = new Municipios();
		$mpios = $mpiosView->selectByIdDepto($_GET["idDepto"]);
				
		echo "<option value='0'>Seleccione Municipio</option>\n";
		foreach($mpios as $reg) {
			echo "<option value='".$reg["id"]."'>".($reg["nombre"])."</option>\n";
		}		
	}

?>