function init() {
     $("#info").hide();
    var ocultar = false;
    $('#div_min').on('click', function () {

        if (ocultar == false) {
            ocultar = true;
            $('#ico_min').attr('class', ' fa fa-plus');

            $('#info').animate({
                left: "-800px"
            }, 500);
        } else {
            $('#ico_min').attr('class', ' fa fa-minus');
            ocultar = false;
            $('#info').animate({
                left: "0px"
            }, 500);
        }
    });

    $('#div_ocultar').on('click', function () {

        $("#info").hide();
    });
    $.post('viewServicesSIGMINFull.php', {loadService: true}, function (resp) {
        if (resp != "")
            eval(resp);
        else
            alert("falla al cargar los servicios geográficos");
    });
}

function toggleControl(element) {
    clearFields();
    $("#infoAL").val("");
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
    $.post('viewServicesSIGMINFullResultados.php', {txtBuscar: document.forms["searchWords"].txtBusqueda.value}, function (resp) {
        if (resp != "") {
            
            $('#info_sc').empty();
            $('#info_sc').append(resp);
            $("#loadingImage").hide();

            $("#info").show();
            $('#ico_min').attr('class', ' fa fa-minus');
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
                url: 'viewLoadCoordinates.php',
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