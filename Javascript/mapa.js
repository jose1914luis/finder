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
                data: {sistemaOrigen: document.forms[1].selGeoSystem.value},
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