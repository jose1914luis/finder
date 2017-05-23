//Las fechas fijas son:
//
//Año nuevo: 1 de enero.
//Día del trabajo: 1 de mayo.
//Grito de independencia: 20 de julio.
//Batalla de Boyacá: 7 de agosto.
//Inmaculada concepción: 8 de diciembre.
//Navidad: 25 de diciembre.
var fechas = ["01-01", "05-01", "07-20", "08-07", "12-08", "12-25"];
var fechasMov = []; //array vacio para calcular los dias festivos

function calcularfecha(fecha, date, ref) {

    var nndate = new Date(date);
    if ($.datepicker.formatDate('mm-dd', date) == fecha) {
        if ($.datepicker.formatDate('mm-D', date) == ref) {
            fechasMov.push($.datepicker.formatDate('yy-mm-dd', date));
        } else {
            for (i = 1; i < 31; i++) {

                nndate.setDate(date.getDate() + i);
                if ($.datepicker.formatDate('mm-D', nndate) == ref) {
                    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
                    break;
                }
            }
        }
    }
}

function checkHolidays(date) {
//calcular semana santa
    domingoPascua(date.getFullYear());
//Epifanía (Reyes magos): primer lunes a partir del 6 de enero.
    calcularfecha('01-06', date, '01-Mon');
//San José: primer lunes a partir del 19 de marzo.
    calcularfecha('03-16', date, '03-Mon');
//San Pedro y San Pablo: primer lunes a partir del 29 de junio.
    calcularfecha('06-29', date, '06-Mon');
//Asunción de la virgen: primer lunes a partir del 15 de agosto.
    calcularfecha('08-15', date, '08-Mon');
//Día de la raza: primer lunes a partir del 12 de octubre.
    calcularfecha('10-12', date, '10-Mon');
//Todos los santos: primer lunes a partir del 1 de noviembre.
    calcularfecha('11-01', date, '11-Mon');
//Independencia de Cartagena: primer lunes a partir del 11 de noviembre.prototype
    calcularfecha('11-11', date, '11-Mon');
    //console.log(fechasMov);
    return fechasMov.indexOf($.datepicker.formatDate('yy-mm-dd', date)) == -1 && fechas.indexOf($.datepicker.formatDate('mm-dd', date)) == -1 && $.datepicker.noWeekends(date);
}
$(function () {
//    $("#dateTerm").datepicker({
//        changeMonth: true,
//        changeYear: true,
//        beforeShowDay: checkHolidays
//    });
//    $("#dateTerm").datepicker("option", "showAnim", "slideDown");
//    $("#dateTerm").datepicker("option", "dateFormat", "yy-mm-dd");
//console.log($("#dateTerm"));
});
var opcion;
function displayMD(id, v) {
    opcion = v;
    var obj = {};
    if (v == 'd') {
        
        for (var i = 1; i < 31; i++) {
            obj[i] = i;
        }
    } else {
        for (var i = 1; i < 13; i++) {
            obj[i] = i;
        }
    }

    $('#selectMD' + id).empty();
    $.each(obj, function (val, text) {
        $('#selectMD' + id).append($('<option>', {
            value: val,
            text: text
        }));
    });
}

var tmp;
var dates;
function domingoPascua(year) {
    if (tmp != year) {
        tmp = year;
    } else {
        return;
    }
    var a = year % 19;
    var b = Math.floor(year / 100);
    var c = year % 100;
    var d = Math.floor(b / 4);
    var e = b % 4;
    var f = Math.floor((b + 8) / 25);
    var g = Math.floor((b - f + 1) / 3);
    var h = (19 * a + b - d - g + 15) % 30;
    var i = Math.floor(c / 4);
    var k = c % 4;
    var l = (32 + 2 * e + 2 * i - h - k) % 7;
    var m = Math.floor((a + 11 * h + 22 * l) / 451);
    var n0 = (h + l + 7 * m + 114)
    var n = Math.floor(n0 / 31) - 1;
    var p = n0 % 31 + 1;
    var date = new Date(year, n, p);
    //Jueves santo: jueves anterior al domingo de pascua.
    var nndate = new Date(date);
    nndate.setDate(date.getDate() - 3);
    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
    //Viernes santo: viernes anterior al domingo de pascua.
    nndate.setDate(date.getDate() - 2);
    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
    nndate.setDate(date.getDate()); //reseteo
    //Ascención de jesús: séptimo lunes después del domingo de pascua.
    nndate.setDate(date.getDate());
    nndate.setDate(date.getDate() + 43);
    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
    //Corpus Christi: décimo lunes después del domingo de pascua.
    nndate = date;
    nndate.setDate(date.getDate() + 64);
    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
    //Sagrado corazón: undécimo lunes después del domingo de pascua.
    nndate = date;
    nndate.setDate(date.getDate() + 7);
    fechasMov.push($.datepicker.formatDate('yy-mm-dd', nndate));
}