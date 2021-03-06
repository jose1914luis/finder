function init() {
    $("#info").hide();
    var ocultar = false;
    $('#infoControl').on('click', function () {

        if (ocultar == false) {
            ocultar = true;
            $('#ico_min').attr('class', 'fa fa-angle-double-right');
            $('#info').animate({
                left: -$("#info_sc").width()
            }, 500);
        } else {
            $('#ico_min').attr('class', ' fa fa-angle-double-left');
            ocultar = false;
            $('#info').animate({
                left: "0px"
            }, 500);
        }
    });
    var options = {
        projection: projection,
        displayProjection: displayProjection,
        units: "meters",
        numZoomLevels: 22
    };
    map = new OpenLayers.Map('map', options);
// map.addControl(new OpenLayers.Control.LayerSwitcher());
    OpenLayers.Lang[OpenLayers.Lang.getCode()]['Base Layer'] = "Capas de Seleccion";
    OpenLayers.Lang[OpenLayers.Lang.getCode()]['Overlays'] = "Capas del Castastro";
    map.addControl(new OpenLayers.Control.LayerSwitcher({'div': OpenLayers.Util.getElement('capas2')}));
// Definici�n de los servicios del Mapa
    var osm = new OpenLayers.Layer.OSM('Relieve');
    var gphy = new OpenLayers.Layer.Google(
            "Fisico",
            {type: google.maps.MapTypeId.TERRAIN, visibility: false}
    );
    var gmap = new OpenLayers.Layer.Google(
            "Calles", // the default
            {numZoomLevels: 20, visibility: false}
    );
    var ghyb = new OpenLayers.Layer.Google(
            "Satelital",
            {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
    );
    var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "solicitudes_col",
                transparent: true,
                format: "image/png"
            },
//{ minScale : 100000000 },
            {opacity: 1, singleTile: true},
            {
                isBaseLayer: false,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: false,
                visibility: true
            }
    );
    var cmqLayerSolTerm = new OpenLayers.Layer.WMS("Solicitudes Archivadas",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "solicitudes_col_term",
                transparent: true,
                format: "image/png"
            },
//{ minScale : 100000000 },
            {opacity: 1, singleTile: true},
            {
                isBaseLayer: false,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: false,
                visibility: true
            }
    );
    var cmqLayerTit = new OpenLayers.Layer.WMS("Titulos",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "titulos_col",
                transparent: true,
                format: "image/png",
                tiled: true
            },
            {opacity: 1},
            {
                isBaseLayer: true,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: false,
                displayOutsideMaxExtent: true,
                visibility: true
            }
    );
    
    var cmqLayerTitTerm = new OpenLayers.Layer.WMS("Titulos Terminados",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "titulos_col_term",
                transparent: true,
                format: "image/png",
                tiled: true
            },
            {opacity: 1},
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
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "Municipios",
                transparent: true,
                format: "image/png"
            },
            {opacity: 1, singleTile: true},
            {
                isBaseLayer: false,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: true,
                visibility: false
            }
    );
    var cmqExcluibles = new OpenLayers.Layer.WMS("Zonas Excluibles",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "zonas_excluibles_col",
                transparent: true,
                format: "image/png"
            },
            {opacity: .45, singleTile: true},
            {
                isBaseLayer: false,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: false,
                visibility: true
            }
    );
    var cmqRestricciones = new OpenLayers.Layer.WMS("Zonas Restrictivas",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "zonas_restriccion_col",
                transparent: true,
                format: "image/png"
            },
            {opacity: .45, singleTile: true},
            {
                isBaseLayer: false,
                buffer: 0,
                // exclude this layer from layer container nodes
                displayInLayerSwitcher: false,
                visibility: true
            }
    );
    var cmqAmbientales = new OpenLayers.Layer.WMS("Autoridades Ambientales",
            'http://www.sigmin.co:8080/geoserver/CMQ/wms', {
                layers: "autoridades_ambientales_col",
                transparent: true,
                format: "image/png"
            },
            {opacity: .95, singleTile: true},
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
        label: "${placa}",
        fontColor: "black",
        fontSize: "18px",
        fontFamily: "Verdana",
        fontWeight: "bold",
        labelOutlineColor: "white",
        labelOutlineWidth: 3
    });
//vectorLayer = new OpenLayers.Layer.Vector("Record", {styleMap: new OpenLayers.StyleMap(style)},{renderers: renderer});

    var stylemap = new OpenLayers.StyleMap(style);
    vectorLayer = new OpenLayers.Layer.Vector("Record", {styleMap: stylemap}, {renderers: renderer});
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
        $.post('viewEvalBuffer.php', {CoordenadasVMC: stringCoords, radioAccion: document.forms["neighbor"].txtRadio.value}, function (resp) {
            if (resp != "")
                eval(resp);
            else
                alert("No hay retorno de informacion");
        });
        CONTAR_POLY++;
        drawControls["point"].deactivate();
        //console.log('se desactiva');
    }
    ;
    function lineAdded(feature) {


        if (ELEMENTO == 'line') {
            measureControls["line"].deactivate(); // 20160309
            drawControls["line"].deactivate();
        } else {

            var stringCoords = feature.geometry.transform(
                    projection,
                    displayProjection
                    ).toString();
            CONTAR_POLY++;
            document.forms["free"].coordenadasPry.value = stringCoords;
            //document.forms["alarm"].coordenadasPry.value = stringCoords;

            drawControls["polygon"].deactivate();
            measureControls["polygon"].deactivate(); // 20160309	

            $.post('?fnd=valida_area', {coordenadasPry: stringCoords}, function (resp) {
                if (resp != "") {
                    var calculo = Number(resp.trim());
                    if (calculo < LIMIT_INFERIOR)
                        $("#infoAL").css("background-color", "#FF0");
                    else if (calculo > LIMIT_SUPERIOR)
                        $("#infoAL").css("background-color", "#FFD2D2");
                    else
                        $("#infoAL").css("background-color", "#CEFFCE");
                    $("#infoAL").val(calculo + " Hect.");
                    //document.calculo_area.infoAL.value = calculo + " Hect.";
                } else
                    alert("No hay retorno de informaci&oacute;n");
            });
        }

    }

    drawControls = {
        point: new OpenLayers.Control.DrawFeature(pointLayer,
                OpenLayers.Handler.Point, {'featureAdded': pointAdded}),
        line: new OpenLayers.Control.DrawFeature(lineLayer,
                OpenLayers.Handler.Path, {'featureAdded': lineAdded}),
        polygon: new OpenLayers.Control.DrawFeature(polygonLayer,
                OpenLayers.Handler.Polygon, {'featureAdded': lineAdded, 'multi': true}),
        box: new OpenLayers.Control.DrawFeature(boxLayer,
                OpenLayers.Handler.RegularPolygon, {
                    handlerOptions: {
                        sides: 4,
                        irregular: true
                    }
                }
        )
    };
    for (var key in drawControls) {
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
            strokeDashstyle: "solid"
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
    measureControls = {// 20160309			
        line: new OpenLayers.Control.Measure(
                OpenLayers.Handler.Path, {
                    persist: true,
                    handlerOptions: {
                        layerOptions: {
                            renderers: renderer,
                            styleMap: styleMap2
                        }
                    }
                }
        ),
        polygon: new OpenLayers.Control.Measure(
                OpenLayers.Handler.Polygon, {
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
    var control; // 20160309
    for (var key in measureControls) {
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

        if (ELEMENTO == 'line') {
            
            if(order == 1) {
                out = measure.toFixed(2) + " " + units;
            } else {
                
                if (units == "km") {
                    measure = 100 * measure;
                    units = "Hects";
                } else if (units == "m") {
                    measure = measure / 10000;
                    units = "Hect";
                }
                
                out = measure.toFixed(2) + " " + units + "<sup>2</" + "sup>";
            }
            
        } else {
            
            if (order == 1) {
                
                out = parseFloat(measure).toFixed(2) + " " + units;
                
            } else {
                
                if (units == "km") {
                    calculo = 100 * measure;
                    units = "Hects";
                } else if (units == "m") {
                    calculo = measure / 10000;
                    units = "Hect";
                } else
                    calculo = measure;
                if (calculo < LIMIT_INFERIOR)
                    $("#infoAL").css("background-color", "#FF0");
                else if (calculo > LIMIT_SUPERIOR)
                    $("#infoAL").css("background-color", "#FFD2D2");
                else
                    $("#infoAL").css("background-color", "#CEFFCE");
            }
            out += parseFloat(calculo).toFixed(2) + " " + units;            
        }
        $("#infoAL").val(out);
    }

    cmqLayerSol.setVisibility(false);
    cmqLayerTit.setVisibility(false);
    cmqLayerSolTerm.setVisibility(false);
    cmqLayerTitTerm.setVisibility(false);
    cmqRestricciones.setVisibility(false);
    cmqExcluibles.setVisibility(false);
    cmqAmbientales.setVisibility(false);
    cmqLayer3.setVisibility(false);
    vectorLayer.setVisibility(true);
    map.addLayers([gphy, ghyb, gmap, osm, cmqLayerSol, cmqLayerSolTerm, cmqLayerTit, cmqLayerTitTerm, vectorLayer, cmqExcluibles, cmqRestricciones, cmqAmbientales, polygonLayer, lineLayer]);
    var click = new OpenLayers.Control.Click();
    map.addControl(click);
    click.activate();
    map.setCenter(new OpenLayers.LonLat(-74.5981636036184, 6.25468647083332).transform(
            displayProjection,
            projection
            ), 6.5);
    $('.Google.Physicalfalse').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Google.Streetsfalse').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Google.Satellitefalse').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Google.Physicaltrue').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Google.Streetstrue').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Google.Satellitetrue').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'blue'});
    $('.Aplications').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'brown'});
    $('.Titles').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'magenta'});
    $('.Record').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'red'});
    $('.Excludable.Areas').qtip({content: {text: false}, position: {corner: {tooltip: 'bottomRight'}}, style: 'green'});
    mover_capas();
}

var ELEMENTO = null;
function toggleControl(element) {
    clearFields();
    $("#infoAL").val("");
    $("#infoAL").css("background-color", "#FFF");
//    //measureControls["polygon"].deactivate();
//    //drawControls["polygon"].deactivate();
//    //measureControls["line"].deactivate();
//    //drawControls["line"].deactivate();
//    
//
    ELEMENTO = $(element).attr('value');
    for (key in drawControls) {
        var control = drawControls[key];
        if (ELEMENTO == key) { //&& element.checked
            if (typeof (measureControls[key]) != "undefined")
                measureControls[key].activate();
            control.activate();
        } else {
//            if (typeof (measureControls[key]) != "undefined")
//                measureControls[key].deactivate();

            control.deactivate();
        }
    }
}

function allowPan(element) {
    var stop = !element.checked;
    for (var key in drawControls) {
        drawControls[key].handler.stopDown = stop;
        drawControls[key].handler.stopUp = stop;
    }
}
function cambiarExpediente(campoPlaca, tipoExp) {
                	$.post('viewValidaExpediente.php', {selExpediente: campoPlaca, tipoExpediente: tipoExp}, function (resp) {
                        	if (resp != "")
                    					eval(resp);
                            	else
                            	alert("No hay retorno de informaci&oacute;n");
    });
           }
function showMultiExpedientes(queryPlacas) {
    vectorLayer.removeAllFeatures();
                	$.post('viewShowMultiExpediente.php', {selExpediente: queryPlacas}, function (resp) {
                        	if (resp != "")
                    					eval(resp);
                            	else
                            	alert("No hay retorno de informaci&oacute;n");
    });
           }
function clearFields() {
    lineLayer.removeAllFeatures();
    pointLayer.removeAllFeatures();
    lineLayer.removeAllFeatures();
    polygonLayer.removeAllFeatures();
    boxLayer.removeAllFeatures();
    vectorLayer.removeAllFeatures();
}
function validarBusqueda() {
    if (document.forms["searchWords"].txtBusqueda.value == "")
        return 0;
    $("#loadingImage").show();
    $.post('viewServicesSIGMINFullResultados.php', {txtBuscar: document.forms["searchWords"].txtBusqueda.value}, function (resp) {
        if (resp != "") {

            $('#info_sc').empty();
            $('#info_sc').append(resp);
            $("#loadingImage").hide();
            $("#info").show();
            $('#ico_min').attr('class', ' fa fa-angle-double-left');
            ocultar = false;
            $('#info').animate({
                left: "0px"
            }, 500);
        } else
            alert("No hay retorno de información");
    });
}

function cambiarProspecto(campoPlaca) {
    $.post('viewValidaPlaca.php', {selProspecto: campoPlaca}, function (resp) {
        if (resp != "")
            eval(resp);
        else
            alert("No hay retorno de informacion");
    });
}

$(function () {
    $("#txtBusqueda").autocomplete({
        source: "viewValidaQuery.php"
    });
    $('#generarArea').hide();
    $('#LiberarArea').hide();
    $('#Perimetral').hide();
    $('.clickable').on('click', function () {
        $('#prospect').css('display', 'none');
        $('#prospect').animate({
            bottom: "-230px",
            left: "550px"
        }, 500);
    });
});
function Pros_Open(ventana) {

    if (ventana == 'generarArea') {
        $('#titulo_panel').text('Generar Area');
        $('#generarArea').show();
        $('#LiberarArea').hide();
        $('#Perimetral').hide();
    } else if (ventana == 'Perimetral') {
        $('#titulo_panel').text('Análisis Perimetral');
        $('#generarArea').hide();
        $('#LiberarArea').hide();
        $('#Perimetral').show();
    } else if (ventana == 'medir') {
        $('#titulo_panel').text('Medir distancia');
    }


    $('#prospect').css('display', 'block');
    $('#prospect').animate({
        bottom: "100px",
        left: "170px"
    }, 500);
    $('#prospect').css('bottom', 'auto');
}

function mover_capas() {
    if (document.getElementById('capas').title == "inicio") {
        $('#capas').animate({
            right: "-50px"
        }, 500);
        document.getElementById('capas').title = "segundo";
    } else {
        $('#capas').animate({
            right: "-250px"
        }, 500);
        document.getElementById('capas').title = "inicio";
    }
}
function mover_tools() {
    if (document.getElementById('tools').title == "inicio") {
        $('#tools').animate({
            right: "-45px"
        }, 500);
        document.getElementById('tools').title = "segundo";
    } else {
        $('#tools').animate({
            right: "-245px"
        }, 500);
        document.getElementById('tools').title = "inicio";
    }
}

function mostrardiv(division) {
    div = document.getElementById(division);
    div.style.display = "";
}
;
function cerrar(division) {
    div = document.getElementById(division);
    div.style.display = "none";
}
;
function ajaxFileUpload() {
    $.ajaxFileUpload(
            {
                url: '/viewLoadCoordinates.php',
                secureuri: false,
                fileElementId: 'fileToUpload',
                dataType: 'execute',
                data: {sistemaOrigen: document.forms[2].selGeoSystem.value},
                success: function (data, status) {
                    if (typeof (data.error) != 'undefined') {
                        if (data.error != '') {
                            alert(data.error);
                        } else {
                            alert(data.msg);
                        }
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            }
    )

    //cerrar('freeGeneratorArea_coordinates');
    return false;
}

function pointAddedCoords() {
    if (document.frmCoordinatesPoint.selGeoSystem.value == "0") {
        alert('Seleccione un Sistema de Coordenadas');
        return 0;
    }

    stringCoords = "POINT(" + document.frmCoordinatesPoint.coordX.value.trim() + " " + document.frmCoordinatesPoint.coordY.value.trim() + ")";
    drawControls["point"].activate();
    var coordenadasRAC = "";
    $.post('viewEvalBuffer.php', {CoordenadasVMC: stringCoords, radioAccion: document.forms["neighbor"].txtRadio.value, sistema_origen: document.frmCoordinatesPoint.selGeoSystem.value}, function (resp) {
        if (resp != "")
            eval(resp);
        else
            alert("No hay retorno de informacion");
    });
    CONTAR_POLY++;
    drawControls["point"].deactivate();
}
;
function ConvertDMSToDD(degrees, minutes, seconds, direction) {
    var dd = degrees + minutes / 60 + seconds / (60 * 60);
    if (direction == "S" || direction == "W") {
        dd = dd * -1;
    } // Don't do anything for N or E
    return dd;
}

function procesarGMS(coordenada) {
    if (coordenada.value.trim() == "")
        return "";
    coordenadas = coordenada.value;
    pos = coordenada.value.split(/[°'"]/);
    if (pos.length == 4) {
        coordenadas = ConvertDMSToDD(Number(pos[0].trim()), Number(pos[1].trim()), Number(pos[2].trim()), pos[3].trim());
    }
    return coordenadas;
}