<?php
			// contenido de los servicios de mapa
			$service = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
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
					"Google Satellite",
					{type: G_HYBRID_MAP, numZoomLevels: 20}
				);

				var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
						"<?php echo $service ?>", {
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
						"<?php echo $service ?>", {
						layers: "titulos_col",
						transparent: true,
						format: "image/png",
						tiled: true
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
						"<?php echo $service ?>",  {
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
						"<?php echo $service ?>", {
						layers: "zonas_excluibles_col",
						transparent: true,
						format: "image/png"
					}, 
					{ opacity: .45, singleTile: true },
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
					strokeColor: "red",//"#E5BE12",
					strokeWidth: 2
					//fillOpacity: 1,
					//label: "Area Expediente"
				});
				vectorLayer = new OpenLayers.Layer.Vector("Expediente", {styleMap: new OpenLayers.StyleMap(style)});

				// Añadir las features a la capa vectorial	

                var polygonLayer = new OpenLayers.Layer.Vector("Polygon Layer");
				GLOBAL_POLY = polygonLayer;

                //map.addLayers([polygonLayer, vectorLayer]);
                map.addControl(new OpenLayers.Control.MousePosition());

				function lineAdded(feature) {
					var stringCoords = feature.geometry.toString();
					CONTAR_POLY ++;
					document.forms["free"].coordenadasPry.value = stringCoords;
					drawControls["polygon"].deactivate();	
					document.forms["free"].boton.value = "Eliminar Poligono";										
				}

                drawControls = {
                    polygon: new OpenLayers.Control.DrawFeature(polygonLayer,
                        OpenLayers.Handler.Polygon,{'featureAdded': lineAdded, 'multi' : true})
                };

				map.addControl(drawControls["polygon"]);
				map.addLayers([ gmap, gphy, ghyb, cmqLayerSol, cmqLayerTit, cmqRestricciones,  cmqLayer3 ]); 
				map.addLayers([polygonLayer, vectorLayer]);
			
				map.setCenter(new OpenLayers.LonLat(-74.5981636036184, 6.25468647083332).transform(
					new OpenLayers.Projection("EPSG:4326"),
					map.getProjectionObject()
				), 10);	
<?php

?>
