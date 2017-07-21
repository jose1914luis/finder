$(function () {
    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
        // Avoid following the href location when clicking
        event.preventDefault();
        // Avoid having the menu to close when clicking
        event.stopPropagation();
        // If a menu is already open we close it
        $('ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
        // opening the one you clicked on
        $(this).parent().addClass('open');

        var menu = $(this).parent().find("ul");
        var menupos = menu.offset();

        if ((menupos.left + menu.width()) + 30 > $(window).width()) {
            var newpos = -menu.width();
        } else {
            var newpos = $(this).parent().width();
        }
        menu.css({left: newpos});

    });
});
function validarDatosUsuario() {

    // Validaci�n de la selecci�n de tipo de documento
    if (document.frmAdminUser.selTipoDocumento.value == 0) {
        alert("'Debe seleccionar un tipo de documento");
        document.frmAdminUser.selTipoDocumento.focus();
        return 0;
    }

    // validacion del numero de documento
    patron = /[0-9]{5,}/;
    if (document.frmAdminUser.txtDocumento.value.search(patron) < 0) {
        alert("'Documento' debe ser num\u00E9rico superior a 5 d\u00EDgitos");
        document.frmAdminUser.txtDocumento.focus();
        return 0;
    }

    // validacion del nombre de la persona:
    patron = /[A-Za-z]{3,}/;
    if (document.frmAdminUser.txtNombre.value.search(patron) < 0) {
        alert("'Nombres' no debe ser inferior a 3 caracteres");
        document.frmAdminUser.txtNombre.focus();
        return 0;
    }

    patron = /[A-Za-z]{3,}/;
    if (document.frmAdminUser.selTipoDocumento.value != 5 && document.frmAdminUser.txtApellido.value.search(patron) < 0) {
        alert("'Apellidos' no debe ser inferior a 3 caracteres");
        document.frmAdminUser.txtApellido.focus();
        return 0;
    }

    // validacion de fecha de nacimiento:
    patron = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/;
    if (document.frmAdminUser.txtFechaNacimiento.value.search(patron) < 0) {
        alert("'Fecha de Nacimiento' no posee caracteres v\u00E1lidos o esta vacio");
        document.frmAdminUser.txtFechaNacimiento.focus();
        return 0;
    }

    // validacion de correo electr�nico:
    patron = /^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
    if (document.frmAdminUser.buyerEmail.value.search(patron) < 0) {
        alert("'Correo Electr\u00F3nico' no posee caracteres v\u00E1lidos o esta vacio");
        document.frmAdminUser.buyerEmail.focus();
        return 0;
    }

    // validar departamento y municipio
    if (document.frmAdminUser.selDepartamento.value == 0) {
        alert("'Debe seleccionar un departamento");
        document.frmAdminUser.selDepartamento.focus();
        return 0;
    }

    if (document.frmAdminUser.selMunicipio.value == 0) {
        alert("'Debe seleccionar un municipio");
        document.frmAdminUser.selMunicipio.focus();
        return 0;
    }

    // validacion de la direcci�n:
    patron = /[A-Za-z]{3,}/;
    if (document.frmAdminUser.txtDireccion.value.search(patron) < 0) {
        alert("'Direcci\u00F3n' no debe ser inferior a 3 caracteres");
        document.frmAdminUser.txtDireccion.focus();
        return 0;
    }

    return 1;
}

function layerConfig(capa) {
    if (controlConfig % 2 > 0)
        mostrardiv(capa);
    else
        cerrar(capa);
    controlConfig++;
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
function mostrarCreditDiv(division) {
    div = document.getElementById(division);
    div.style.display = "block";
}
;
function cerrarCreditDiv(division) {
    div = document.getElementById(division);
    div.style.display = "none";
}
;

function confirmaCredito(url) {
    if (confirm("Desea consumir Cr\u00E9ditos?"))
        window.open(url, '_blank');
    else
        document.location.href = "";
}

function loadCreditosProspectos(prospecto) {
    var creditosDiv = '<table width="100%" align="center" cellspacing="0" class="creditFont"><tr bgcolor="#000" style="color:#fff"><td colspan="4" align="center">Administracion de Cr&eacute;ditos</td><td align="right"><a href="javascript:" onclick="cerrarCreditDiv(\'' + prospecto + '\')" style="text-decoration:none"><font color="#fff">[X]</font></a></td><td></td></tr><tr><td align="center"><a href="?crd=prospecto&placa=' + prospecto + '&clasificacion=PROSPECTO" target="_blank"><img src="Imgs/crd_report.png" width="40" height="40" title="Ver prospecto"/></a></td><td align="center"><a href="javascript:confirmaCredito(\'?crd=prospecto&placa=' + prospecto + '&clasificacion=PROSPECTO&credits=1\')"><img src="Imgs/crd_coords.png"  width="40" height="40" title="Descargar Coordenadas"/></a></td><td align="center"><a href="javascript:confirmaCredito(\'?crd=prospecto&placa=' + prospecto + '&clasificacion=ESTUDIO_TECNICO_PROSPECTO&credits=1\')"><img src="Imgs/crd_area_libre.png"  width="40" height="40" title="Descargar Reporte Area Libre"/></a></td><td align="center"><a href="javascript:confirmaCredito(\'?crd=dwn_shapes&placa=' + prospecto + '&credits=1\')"><img src="Imgs/crd_shape.png"  width="40" height="40" title="Descargar Shape"/></a></td><td align="center"><a href="javascript:confirmaCredito(\'?crd=superposiciones_area&placa=' + prospecto + '&credits=1\')"><img src="Imgs/superposicionesIcon.png" width="40" height="40" title="Descarga Listado de Superposiciones"/></a></td></tr><tr><td align="center">Free</td><td align="center">1 Cr&eacute;d</td><td align="center">5 Cr&eacute;ds</td><td align="center">7 Cr&eacute;ds</td><td>7 Cr&eacute;ds</td></tr></table>';

    cerrarCreditDiv(divAnterior);
    $("#" + prospecto).html(creditosDiv);
    $("#" + divAnterior).html("");
    mostrarCreditDiv(prospecto);
    divAnterior = prospecto;

}

function enviar_descarga() {
    if (confirm("Desea consumir Cr\u00E9ditos de Descarga?")) {
        document.forms[1].action = '?crd=dwn_expedientes&credits=1';
        document.forms[1].target = '_blank';
        document.forms[1].submit();
    }
}

function valida_placas() {
//    console.log('entro');
//    document.forms[0].action = '?mnu=descargas';
//    document.forms[0].target = '_top';
//    document.forms[0].target = '_blank';
//    document.forms[0].submit();
}

function inactivarPlaca(placa) {
    if (confirm("Est\u00E1 seguro de inactivar el expediente " + placa + "?"))
        document.location.href = "?mnu=expedientes&placa=" + placa;
}


function eliminarProspecto(placa) {
    if (confirm("Est\u00E1 seguro de eliminar el prospecto " + placa + "?")) {
        document.prospectos.placa.value = placa;
        document.prospectos.act.value = "delete";
        document.prospectos.submit();
    }
}

function creditosRelease(opcion) {
    if (confirm("Desea consumir cr\u00E9ditos por este servicio?")) {
        document.frmCreditosRelease.compraRelease.value = opcion;
        document.frmCreditosRelease.submit();
    }
}	