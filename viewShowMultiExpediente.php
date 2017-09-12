<?php
	require_once("Modelos/ReportGenerator.php");
	require_once("Acceso/Config.php"); // Definici�n de las variables globales	

	$pryView = new ReportGenerator(); 
	
if(!empty($_POST["selExpediente"])) {
	$coords = $pryView->generarViewMultiMap($_POST["selExpediente"]);

?>
$( document ).ready(function() {
		vectorLayer.removeAllFeatures();
<?php
	foreach($coords as $cadaPoly) {
		if( $cadaPoly["coordenadas"]!="MULTIPOLYGON EMPTY") {
?>
                 console.log("<?php echo $cadaPoly["coordenadas"] ?>");
			polygonFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.fromWKT("<?php echo $cadaPoly["coordenadas"] ?>").transform(
									displayProjection,
									projection
								  ));
			polygonFeature.attributes = {
				placa: "<?php echo $cadaPoly["placa"] ?>"
			};			
			
			vectorLayer.addFeatures([polygonFeature]);
		
<?php		
		}
	}	
?>
                        
                console.log(map);
		bounds = vectorLayer.getDataExtent();
		map.zoomToExtent(bounds);
                
                
});
<?php
	}
?>
