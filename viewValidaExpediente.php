<?php
	@session_start();
	
	require_once("Modelos/ReportGenerator.php");
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/EstadisticasUsuarios.php"); 


if(!empty($_POST["selExpediente"])) {

	$pryView = new ReportGenerator(); 
	$coords = $pryView->generarViewMap($_POST["selExpediente"], $_POST["tipoExpediente"]);
	$coordenadas = $coords["coordenadas"];
	if($coordenadas != "") {
		$controlUsuario = new EstadisticasUsuarios();
		$estado = $controlUsuario->setEstadisticasPlaca($_SESSION['usuario_sgm'], $_POST["selExpediente"] );			
	
?>

		vectorLayer.removeAllFeatures();
		polygonFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas ?>").transform(
								displayProjection,
								projection
							  ));
		polygonFeature.attributes = {
			placa: "<?php echo $_POST["selExpediente"] ?>"
		}
		vectorLayer.addFeatures([polygonFeature]);
		bounds = vectorLayer.getDataExtent();
		map.zoomToExtent(bounds);

<?php 
	} else {
		echo utf8_encode("alert('El código del expediente minero ".$_POST["selExpediente"]." no está definido en el sistema');");
	}
}
?>
