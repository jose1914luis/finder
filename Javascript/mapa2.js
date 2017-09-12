function informacion() {
    alert('Para obtener mas información debes registrarte como usuario SIGMIN');
}
function init() {
    var ocultar = false;
    $('#div_min').on('click', function () {

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



    $.post('/viewServicesSIGMINFull_1.php', {loadService: true}, function (resp) {
        if (resp != "") {
            eval(resp);
            if (window.location.href.indexOf("/buscar/") > -1) {
                $("#info").show();
                var ocultar = false;
                $('#ico_min').attr('class', ' fa fa-angle-double-left');
                $('#info').animate({
                    left: "0px"
                }, 500);
                showMultiExpedientes(" s.placa='CERTIFICA-AREA-02' OR  0=1 ");
            } else {
                $("#info").hide();
            }
        } else {
            alert("falla al cargar los servicios geográficos");
        }
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
                	$.post('/viewValidaExpediente.php', {selExpediente: campoPlaca, tipoExpediente: tipoExp}, function (resp) {
                        	if (resp != "")
                    					eval(resp);
                            	else
                            	alert("No hay retorno de informaci&oacute;n");
    });
           }
function showMultiExpedientes(queryPlacas) {
    console.log(queryPlacas);
    vectorLayer.removeAllFeatures();
                	$.post('/viewShowMultiExpediente.php', {selExpediente: queryPlacas}, function (resp) {
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
    $.post('/viewServicesSIGMINFullResultados_1.php', {txtBuscar: $('#txtBusqueda').val()}, function (resp) {
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
    $.post('/viewValidaPlaca.php', {selProspecto: campoPlaca}, function (resp) {
        if (resp != "")
            eval(resp);
        else
            alert("No hay retorno de informacion");
    });
}

$(function () {
    $("#txtBusqueda").autocomplete({
        source: "/viewValidaQuery.php"
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
