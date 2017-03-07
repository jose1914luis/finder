<?php
	$servicio 		= "http://www.sigmin.co:8080/geoserver/CMQ/wms";
?>

	map = new OpenLayers.Map('map');
	map.addControl(new OpenLayers.Control.LayerSwitcher());
	
	
	var gphy = new OpenLayers.Layer.Google(
		"Google Physical",
		{type: G_PHYSICAL_MAP}
	);
	var gmap = new OpenLayers.Layer.Google(
		"Google Streets", // the default
		{numZoomLevels: 20}
	);
	var ghyb = new OpenLayers.Layer.Google(
		"Google Hybrid",
		{type: G_HYBRID_MAP, numZoomLevels: 20}
	);
	var gsat = new OpenLayers.Layer.Google(
		"Google Satellite",
		{type: G_SATELLITE_MAP, numZoomLevels: 22}
	);

	var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
			"<?php echo $servicio ?>", {
			layers: "solicitudes_col",
			transparent: true,
			format: "image/png"
		}, 
		{ opacity: 1, singleTile: true },
		{
			isBaseLayer: false,
			buffer: 0,
			// exclude this layer from layer container nodes
			displayInLayerSwitcher: false,
			visibility: true
		}				
	);	

	var cmqLayerTit = new OpenLayers.Layer.WMS("Titulos",
			"<?php echo $servicio ?>",  {
			layers: "titulos_col",
			transparent: true,
			format: "image/png",
			tiled: true
			//tilesOrigin : map.maxExtent.left + ',' + map.maxExtent.bottom										
		}, 
		{ opacity: 1},
		{
			isBaseLayer: true,
			buffer: 0,
			// exclude this layer from layer container nodes
			displayInLayerSwitcher: false,
			displayOutsideMaxExtent: true,
			visibility: true
		}				
	);	
		

	var cmqLayer3 = new OpenLayers.Layer.WMS("Municipios",
			"<?php echo $servicio ?>",   {
			layers: "Municipios",
			transparent: true,
			format: "image/png"
		}, 
		{ opacity: 1, singleTile: true },
		{
			isBaseLayer: false,
			buffer: 0,
			// exclude this layer from layer container nodes
			displayInLayerSwitcher: false,
			visibility: true
		}
	);

	var cmqRestricciones = new OpenLayers.Layer.WMS("Zonas Excluibles",
			"<?php echo $servicio ?>",  {
			layers: "zonas_excluibles_col",
			transparent: true,
			format: "image/png"
		}, 
		{ opacity: .55, singleTile: true },
		{
			isBaseLayer: false,
			buffer: 0,
			// exclude this layer from layer container nodes
			displayInLayerSwitcher: false,
			visibility: true
		}				
	);	

	// Crear una capa vectorial
	
	var style = new OpenLayers.Style({
		fill: 	 false, 
		strokeColor: "red",
		strokeWidth: 2
	});

	var vectorLayer = new OpenLayers.Layer.Vector("Punto de An&aacute;lisis"); //, {styleMap: new OpenLayers.StyleMap(style)});
	
	
	// Añadir las features a la capa vectorial	
	polygonLayer = new OpenLayers.Layer.Vector("Radio de Influencia", {styleMap: new OpenLayers.StyleMap(style)});
	GLOBAL_POLY = vectorLayer;

	
	//map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.addControl(new OpenLayers.Control.MousePosition());

	function pointAdded(feature) {		
		var stringCoords = feature.geometry.toString();
		var coordenadasRAC = "";
							
		$.post('viewEvalBuffer.php', { CoordenadasVMC : stringCoords, radioAccion: document.forms["neighbor"].txtRadio.value}, function(resp) {
				if(resp!="") 
					eval(resp);
				else
					alert("No hay retorno de informacion");
			});				
		
		CONTAR_POLY ++;					
		
		drawControls["point"].deactivate();						
		document.forms["neighbor"].boton.value = "Borrar Poligono";										
	};				
	
	drawControls = {
		point: 		new OpenLayers.Control.DrawFeature(vectorLayer, OpenLayers.Handler.Point, {'featureAdded': pointAdded})
	};

	//map.addControl(drawControls["polygon"]); //, drawControls["point"]);
	for(var key in drawControls) {
		map.addControl(drawControls[key]);
	};
	
	
	map.addLayers([ gmap, gphy, ghyb, cmqLayerSol, cmqLayerTit, cmqRestricciones,  cmqLayer3 ]); 
	map.addLayers([polygonLayer, vectorLayer]);
	
	

	map.setCenter(new OpenLayers.LonLat(-74.5981636036184, 6.25468647083332).transform(
		new OpenLayers.Projection("EPSG:4326"),
		map.getProjectionObject()
	), 10);	
	
<?php

?>	
