function init() {
    $("#info").hide();

    var ocultar = false;
    $('#div_min').on('click', function () {

        if (ocultar == false) {
            ocultar = true;
            $('#ico_min').attr('class', 'fa fa-angle-double-right');

            $('#info').animate({
                left: "-800px"
            }, 500);
        } else {
            $('#ico_min').attr('class', ' fa fa-angle-double-left');
            ocultar = false;
            $('#info').animate({
                left: "0px"
            }, 500);
        }
    });

//    $('#div_ocultar').on('click', function () {
//
//        $("#info").hide();
//    });


    $.post('viewServicesSIGMINFull_1.php', {loadService: true}, function (resp) {
        if (resp != "")
            eval(resp);
        else
            alert("falla al cargar los servicios geográficos");
    });
}

function toggleControl(element) {
    clearFields();
    document.calculo_area.infoAL.value = "";
    $("#infoAL").css("background-color", "#FFF");
    measureControls["polygon"].deactivate();
    drawControls["polygon"].deactivate();

    if (element.id == 'poligono')
    {
        document.getElementById("polygonToggle").click();
        document.tools.polygonToggle.click();
    }
    if (element.id == 'area')
    {
        document.getElementById("pointToggle").click();
        document.tools.point.click();
    }
    for (key in drawControls) {
        var control = drawControls[key];
        if (element.value == key && element.checked) {
            if (typeof (measureControls[key]) != "undefined")
                measureControls[key].activate();

            control.activate();
        } else {
            if (typeof (measureControls[key]) != "undefined")
                measureControls[key].deactivate();

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
    $('#info_sc').empty();
    $.post('viewServicesSIGMINFullResultados_1.php', {txtBuscar: $('#txtBusqueda').val()}, function (resp) {
        if (resp != "") {
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
    var colors = ["red", "green", "blue"];
    colors[0];
});

function Busc_Open() {
    openProspect = 0;
    visualizar = 'NO';
    if (openSearch % 2 == 0)
        visualizar = 'SI';
    openSearch++;

    if (visualizar == "SI") {
        $('#prospect').css('display', 'none');
        $('#prospect').animate({
            bottom: "-230px",
            left: "550px"
        }, 500);

        $('#buscar').css('display', 'block');
        $('#buscar').animate({
            bottom: "350px",
            left: "170px"
        }, 500);
    } else {
        $('#buscar').css('display', 'none');
        $('#buscar').animate({
            bottom: "20px",
            left: "520px"
        }, 500);
    }
}

function Pros_Open() {
    openSearch = 0;
    visualizar = 'NO';
    if (openProspect % 2 == 0)
        visualizar = 'SI';
    openProspect++;

    if (visualizar == "SI") {
        $('#buscar').css('display', 'none');
        $('#buscar').animate({
            bottom: "20px",
            left: "520px"
        }, 500);

        $('#prospect').css('display', 'block');
        $('#prospect').animate({
            bottom: "100px",
            left: "170px"
        }, 500);
    } else {
        $('#prospect').css('display', 'none');
        $('#prospect').animate({
            bottom: "-230px",
            left: "550px"
        }, 500);
    }
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