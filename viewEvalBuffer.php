<?php
	require_once("Modelos/ProspectosBogSGM.php");
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ReportGenerator.php");	
	require_once("Modelos/CreditosUsuarios.php");

	$coordsFinder = $_POST["CoordenadasVMC"];
	
	if(!empty($_POST["sistema_origen"]) && @$_POST["sistema_origen"]!= "WGS84") {
		$transform	  = new ReportGenerator(); 
		$coordsFinder = $transform->get_GaussToWGS84($coordsFinder, $_POST["sistema_origen"]);
	} 
	
	$pryView 		= new ProspectosBogSGM(); 
	$coordenadas 	= $pryView->get_CoordsPuntoByBuffer($coordsFinder, $_POST["radioAccion"]);	
	
	// calculo del área a evaluar
	$cred 			= new CreditosUsuarios();
	$areaCalculada	= $cred->getArea($coordenadas);	
	
	if($coordenadas != "") {	
?>
		document.calculo_area.infoAL.value = "<?=$areaCalculada?> Hect.";

		polygonLayer.removeAllFeatures();
		vectorLayer.removeAllFeatures();
		polygonFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas ?>").transform(
						displayProjection,
						projection
					));	
		polygonFeature.attributes = {
			placa: "Influence Ratio"
		};
		vectorLayer.addFeatures([polygonFeature]);
		bounds = vectorLayer.getDataExtent();
		map.zoomToExtent(bounds);		
		coordenadasRAC = "<?php echo $coordenadas ?>";
		document.forms["neighbor"].coordenadasRAC.value = coordenadasRAC;
		
<?php 
	} 
?>
