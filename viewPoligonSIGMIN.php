<?php

	$servicio 		= "http://www.sigmin.co:8080/geoserver/CMQ/wms";
	$coordenadas 	= $_POST["CoordenadasPry"];

	?>
	
		var osm = new OpenLayers.Layer.OSM();
	
        var gmap = new OpenLayers.Layer.Google(
				"Google Streets", // the default
				{numZoomLevels: 20, visibility: false}
			),
			gsat = new OpenLayers.Layer.Google(
				"Google Satellite", 
				{type: google.maps.MapTypeId.SATELLITE, transparent: true, numZoomLevels: 22}
			),
			gphy = new OpenLayers.Layer.Google(
				"Google Physical",
				{type: google.maps.MapTypeId.TERRAIN, visibility: false}
			),
			ghyb = new OpenLayers.Layer.Google(
				"Google Hybrid",
				{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
			),
			projection = new OpenLayers.Projection("EPSG:900913"),
			displayProjection = new OpenLayers.Projection("EPSG:4326");	

		var
			options = {
			  controls: [
				new OpenLayers.Control.Navigation(),
				new OpenLayers.Control.PanZoomBar(),
				new OpenLayers.Control.LayerSwitcher(),
				new OpenLayers.Control.MouseDefaults(),
				new OpenLayers.Control.KeyboardDefaults()
			  ],
			  projection: projection,
			  displayProjection: displayProjection,
			  units: "meters",
			  numZoomLevels: 22 /* 18 */
			};		
			
            map = new OpenLayers.Map('map', options);
			
            map.addControl(new OpenLayers.Control.LayerSwitcher());
			     
			var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
					"<?php echo $servicio ?>", 
				{
                    layers: "solicitudes_col",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 1, singleTile: true },
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: true,
                    visibility: true
                }	
            );	

			var cmqLayerTit = new OpenLayers.Layer.WMS("Titulos",
					"<?php echo $servicio ?>", { 
                    layers: "titulos_col",
                    transparent: true,
                    format: "image/png",
                    tiled: true
                }, 
				{ opacity: 1},
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
					displayOutsideMaxExtent: true,
                    visibility: true
                }				
            );	
				
			var cmqLayer3 = new OpenLayers.Layer.WMS("Municipios",
					"<?php echo $servicio ?>", {
                    layers: "Municipios",
                    transparent: true,
                    format: "image/png",
					tiled: true
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
					"<?php echo $servicio ?>", {
					layers: "zonas_excluibles_col",
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

				// crear una feature polygon
				var polygonFeature = new OpenLayers.Feature.Vector(
					new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas ?>").transform(
						displayProjection,
						projection						
					)
				);

				// Crear una capa vectorial
				var vectorLayer = new OpenLayers.Layer.Vector("Area Consultada");

			// Añadir las features a la capa vectorial
				vectorLayer.addFeatures(
						[polygonFeature]);
			

            map.addLayers([ ghyb, gmap, gphy, osm, cmqLayerSol, cmqLayerTit, cmqLayer3, cmqRestricciones, vectorLayer]); 

			bounds = vectorLayer.getDataExtent();
			map.zoomToExtent(bounds);
			
<?php

?>
