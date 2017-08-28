var winP = null, resultados = null;
var map, drawControls, polygonFeature, vectorLayer, pointLayer, lineLayer, polygonLayer, boxLayer;
var openSearch = 0, openProspect = 0;

var measureControls; // 20160309
var CONTAR_POLY = 0;
var GLOBAL_POLY;

OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;


// dar color al layer de resultados
var styleMap = new OpenLayers.StyleMap({'strokeWidth': 5, 'strokeColor': '#ff0000'});


// Definición de los sistemas de proyección:
var projection = new OpenLayers.Projection("EPSG:900913");
var displayProjection = new OpenLayers.Projection("EPSG:4326");

vectorLayer = new OpenLayers.Layer.Vector("Vector Layer",
        {
            styleMap: styleMap,
            projection: projection,
            displayProjection: displayProjection
        });

OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
    defaultHandlerOptions: {
        'single': true,
        'double': false,
        'pixelTolerance': 0,
        'stopSingle': false,
        'stopDouble': false
    },
    initialize: function (options) {
        this.handlerOptions = OpenLayers.Util.extend(
                {}, this.defaultHandlerOptions
                );
        OpenLayers.Control.prototype.initialize.apply(
                this, arguments
                );
        this.handler = new OpenLayers.Handler.Click(
                this, {
                    'click': this.trigger
                }, this.handlerOptions
                );
    },
    trigger: function (e) {
        var lonlat = map.getLonLatFromPixel(e.xy);
        lonlat = lonlat.transform(
                projection,
                displayProjection
                );
        coordenadas = "POINT(" + lonlat.lon + " " + lonlat.lat + ")";

                  	$.post('?fnd=identify_map', {coordenadasRAC: coordenadas}, function (resp) {
            $("#loadingImage").show();
                            	if (resp != "") {

                console.log('resp: ' + resp);
                $('#info_sc').empty();
                $('#info_sc').append(resp);
                $("#loadingImage").hide();

                $("#info").show();
                $('#ico_min').attr('class', ' fa fa-angle-double-left');
                ocultar = false;
                $('#info').animate({
                    left: "0px"
                }, 500);

//                if (resultados != null)
//                    resultados.close();
//                resultados = window.open("", "Ventana", "width=700 height=200 scrollbars=yes");
//                resultados.document.title = ":: SIGMIN - Identify";
//                resultados.document.write(resp);
//                resultados.focus();
//                $("#loadingImage").hide();
            } else
                                	alert("No hay retorno de informaci&oacute;n");
        });
    }
});