<?php
			// contenido de los servicios de mapa
                        $service = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
			//$service = "https://www.sigmin.co:8443/geoserver/CMQ/wms";
?>

    var options = {	
      projection: projection,
      displayProjection: displayProjection,
      units: "meters",
      numZoomLevels: 22
    };


	map = new OpenLayers.Map('map', options);
	// map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.addControl(new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('capas2')}));
	
	// Definici�n de los servicios del Mapa
				var osm = new OpenLayers.Layer.OSM();

				
				var gphy = new OpenLayers.Layer.Google(
					"Google Physical",
					{type: google.maps.MapTypeId.TERRAIN, visibility: false}
				);
				var gmap = new OpenLayers.Layer.Google(
					"Google Streets", // the default
					{numZoomLevels: 20, visibility: false}
				);

				var ghyb = new OpenLayers.Layer.Google(
					"Google Satellite",
					{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
				);	

				var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
						"<?php echo $service ?>", {
						layers: "solicitudes_col",
						transparent: true,
						format: "image/png"
					},
					//{ minScale : 100000000 },
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
					{  opacity: 1},
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
					{  opacity: 1, singleTile: true },
					{
						isBaseLayer: false,
						buffer: 0,
						// exclude this layer from layer container nodes
						displayInLayerSwitcher: true,
						visibility: false
					}
				);

				var cmqExcluibles = new OpenLayers.Layer.WMS("Zonas Excluibles",
						"<?php echo $service ?>", {
						layers: "zonas_excluibles_col",
						transparent: true,
						format: "image/png"
					}, 
					{ opacity: .45,  singleTile: true },
					{
						isBaseLayer: false,
						buffer: 0,
						// exclude this layer from layer container nodes
						displayInLayerSwitcher: false,
						visibility: true
					}				
				);	
				
				var cmqRestricciones = new OpenLayers.Layer.WMS("Zonas Restrictivas",
						"<?php echo $service ?>", {
						layers: "zonas_restriccion_col",
						transparent: true,
						format: "image/png"
					}, 
					{ opacity: .45,  singleTile: true },
					{
						isBaseLayer: false,
						buffer: 0,
						// exclude this layer from layer container nodes
						displayInLayerSwitcher: false,
						visibility: true
					}				
				);
				
				var cmqAmbientales = new OpenLayers.Layer.WMS("Autoridades Ambientales",
						"<?php echo $service ?>", {
						layers: "autoridades_ambientales_col",
						transparent: true,
						format: "image/png"
					}, 
					{ opacity: .95,  singleTile: true },
					{
						isBaseLayer: false,
						buffer: 0,
						// exclude this layer from layer container nodes
						displayInLayerSwitcher: false,
						visibility: true
					}				
				);				
				
				var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
				renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;
				// Crear una capa vectorial
				
				var style = new OpenLayers.Style({
             			strokeColor: "#FF0000",
			                strokeOpacity: 1,
			                strokeWidth: 3,
			                fillColor: "#FF0000",
			                fillOpacity: 0.3,
			                pointRadius: 2,
			                pointerEvents: "visiblePainted",
			                label : "${placa}",                    
		                        fontColor: "black",
		                        fontSize: "18px",
		                        fontFamily: "Verdana",
		                        fontWeight: "bold",
		                        labelOutlineColor: "white",
		                        labelOutlineWidth: 3
				});
				//vectorLayer = new OpenLayers.Layer.Vector("Record", {styleMap: new OpenLayers.StyleMap(style)},{renderers: renderer});

var stylemap  = new OpenLayers.StyleMap(style);
vectorLayer = new OpenLayers.Layer.Vector("Record", {styleMap: stylemap},{renderers: renderer});
				
				
				
                pointLayer = new OpenLayers.Layer.Vector("Point Layer");
                lineLayer = new OpenLayers.Layer.Vector("Line Layer");
                polygonLayer = new OpenLayers.Layer.Vector("Polygon Layer");
                boxLayer = new OpenLayers.Layer.Vector("Box layer");
                map.addControl(new OpenLayers.Control.MousePosition());
				GLOBAL_POLY = polygonLayer;

				function pointAdded(feature) {		
					var stringCoords = feature.geometry.transform(
						projection,
						displayProjection
					).toString();
					
				
					var coordenadasRAC = "";			
					$.post('viewEvalBuffer.php', { CoordenadasVMC : stringCoords, radioAccion: document.forms["neighbor"].txtRadio.value}, function(resp) {
							if(resp!="") 
								eval(resp);
							else
								alert("No hay retorno de informacion");
						});				
					CONTAR_POLY ++;						
					drawControls["point"].deactivate();													  
				};	

				function lineAdded(feature) {
				
					var stringCoords = feature.geometry.transform(
						projection,
						displayProjection
					).toString();	
					
					CONTAR_POLY ++;
					document.forms["free"].coordenadasPry.value = stringCoords;
					//document.forms["alarm"].coordenadasPry.value = stringCoords;
					drawControls["polygon"].deactivate();

					measureControls["polygon"].deactivate();	// 20160309		
							
					$.post('?fnd=valida_area', { coordenadasPry: stringCoords}, function(resp) {
						if(resp!="") {
							var calculo = Number(resp.trim());
							if(calculo < LIMIT_INFERIOR)
								$("#infoAL").css("background-color","#FF0");
							else if(calculo > LIMIT_SUPERIOR)
								$("#infoAL").css("background-color","#FFD2D2");
							else
								$("#infoAL").css("background-color","#CEFFCE");							
							
                                                                $("#infoAL").val( calculo + " Hect.");
								//document.calculo_area.infoAL.value = calculo + " Hect.";
							} else
								alert("No hay retorno de informaci&oacute;n");
						});												
				}

                drawControls = {
                    point: new OpenLayers.Control.DrawFeature(pointLayer,
                        OpenLayers.Handler.Point, {'featureAdded': pointAdded}),
                    line: new OpenLayers.Control.DrawFeature(lineLayer,
                        OpenLayers.Handler.Path),
                    polygon: new OpenLayers.Control.DrawFeature(polygonLayer,
                        OpenLayers.Handler.Polygon,{'featureAdded': lineAdded, 'multi' : true}),
                    box: new OpenLayers.Control.DrawFeature(boxLayer,
                        OpenLayers.Handler.RegularPolygon, {
                            handlerOptions: {
                                sides: 4,
                                irregular: true
                            }
                        }
                    )
                };

                for(var key in drawControls) {
                    map.addControl(drawControls[key]);
                }	


		// allow testing of specific renderers via "?renderer=Canvas", etc
		// var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;		// 20160309
		//renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;	// 20160309		

					// style the sketch fancy
		var sketchSymbolizers = {
			"Point": {
				pointRadius: 4,
				graphicName: "square",
				fillColor: "white",
				fillOpacity: 1,
				strokeWidth: 1,
				strokeOpacity: 1,
				strokeColor: "#333333"
			},
			"Line": {
				strokeWidth: 3,
				strokeOpacity: 1,
				strokeColor: "#666666",
				strokeDashstyle: "dash"
			},
			"Polygon": {
				strokeWidth: 2,
				strokeOpacity: 1,
				strokeColor: "#666666",
				fillColor: "white",
				fillOpacity: 0.3
			}
		};
		var style2 = new OpenLayers.Style();
		style2.addRules([
			new OpenLayers.Rule({symbolizer: sketchSymbolizers})
		]);
		var styleMap2 = new OpenLayers.StyleMap({"default": style2});

		measureControls = {		// 20160309			
			line: new OpenLayers.Control.Measure(
				OpenLayers.Handler.Path, {
					persist: true,
					handlerOptions: {
						layerOptions: {
							renderers: renderer,
							styleMap:  styleMap2
						}
					}
				}
			),   
			polygon: new OpenLayers.Control.Measure(
				OpenLayers.Handler.Polygon , {
					persist: true,
					handlerOptions: {
						layerOptions: {
							renderers: renderer,
							styleMap: styleMap2
						}
					}
				} 
			)				
		};	

		var control;		// 20160309
		for(var key in measureControls) {
			control = measureControls[key];
			control.events.on({
				"measure": handleMeasurements,
				"measurepartial": handleMeasurements
			});
			map.addControl(control);
		}


		function handleMeasurements(event) {		// 20160309
			var geometry = event.geometry;
			var units = event.units;
			var order = event.order;
			var measure = event.measure;
			var calculo;
			var out = "";
			if(order == 1) {
				out = measure.toFixed(2) + " " + units;
			} else {
				if(units=="km")	{ 
					calculo = 100*measure;
					units = "Hects";
				} else if (units=="m") {
					calculo = measure/10000;
					units = "Hect";						
				} else
					calculo = measure;
				
				if(calculo < LIMIT_INFERIOR)
					$("#infoAL").css("background-color","#FF0");
				else if(calculo > LIMIT_SUPERIOR)
					$("#infoAL").css("background-color","#FFD2D2");
				else
					$("#infoAL").css("background-color","#CEFFCE");
				
			}
			out += calculo.toFixed(2) + " " + units;
                        $("#infoAL").val(out);
		}
		
		cmqLayerSol.setVisibility(false);
		cmqLayerTit.setVisibility(false);
		cmqRestricciones.setVisibility(false);
		cmqExcluibles.setVisibility(false);
		cmqAmbientales.setVisibility(false);
		cmqLayer3.setVisibility(false);
		vectorLayer.setVisibility(true);
                
		map.addLayers([gphy, ghyb, gmap, osm, cmqLayerSol, cmqLayerTit, vectorLayer, cmqExcluibles, cmqRestricciones, cmqAmbientales, polygonLayer]);
				
		var click = new OpenLayers.Control.Click();
                map.addControl(click);
                click.activate();
			

		map.setCenter(new OpenLayers.LonLat(-74.5981636036184, 6.25468647083332).transform(
			displayProjection,
			projection
		), 6.5);	

		 $('.Google.Physicalfalse').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
                $('.Google.Streetsfalse').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
                $('.Google.Satellitefalse').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
                $('.Google.Physicaltrue').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
                $('.Google.Streetstrue').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
                $('.Google.Satellitetrue').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'blue'});
  				$('.Aplications').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'brown'});
                $('.Titles').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'magenta'});
  				$('.Record').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'red'});
                $('.Excludable.Areas').qtip({content:{text:false},position:{corner:{tooltip: 'bottomRight'}},style:'green'});
