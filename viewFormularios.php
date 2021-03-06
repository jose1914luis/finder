<?php
session_start();

require_once("Acceso/Config.php"); // Definici�n de las variables globales	
require_once("Modelos/Plantillas.php");
require_once("Modelos/DocumentosPlantillas.php");
require_once("Modelos/ActoresDocumentos.php");
require_once("Modelos/TiposRequerimientos.php");
require_once("Validaciones/ValidacionesFormularios.php");

if (!empty($_GET["idPlantilla"])) {

    $codigoExp = $_GET['codigoExp'];
    // identificador de empresa tomara este valor como global en el sitio web
//    $_SESSION["idEmpresa"] = $_GET["idEmpresa"];

    $template = new Plantillas();
    $docTemplates = new DocumentosPlantillas();
    $solRequerimientos = new ActoresDocumentos();
    $tipoRequerimientos = new TiposRequerimientos();

    $indices = $template->selectIndicesByPlantilla($_GET["idPlantilla"]);
    $rutaImagen = $GLOBALS["docDigital"];

    $validador = new ValidacionesFormularios();
    echo $validador->validaIndexamiento($indices);
    ?>    
      
    <script src="Javascript/calendario.js" type="text/javascript"></script>
    
    <script>

        var ListaRequerimientos = "";

        function loadDocRequeridos(placa) {
            if (placa != "") {
                //                $.post('viewDocumentosRequieren.php', {Placa: placa.toUpperCase()}, function (resp) {
                //                    if (resp != "") {
                //                        ListaRequerimientos = resp;
                //                    } else
                //                        alert("No hay retorno de informaci&oacute;n");
                //                });
            }
            $('#codigoExpediente').attr('readonly', true);
        }
        ;
        loadDocRequeridos(<?= "'" . $codigoExp . "'" ?>);

        function mostrarRequerimientos() {
            return (ListaRequerimientos);
        }

        $('#cantidadImagenes').blur(function () {
            this.value = (!isNaN(parseInt(this.value.trim()))) ? parseInt(this.value.trim()) : "";
        });
        $('#codigoExpediente').blur(function () {
            this.value = this.value.toUpperCase();
        });

        // Definici�n de variables para generar requrimientos
        indexReq = 1;

        function delDivReq(nameDiv) {
            $(nameDiv).remove();
        }


        function insertDivReq() {
            if (document.frm01.codigoExpediente.value.trim() == "") {
                alert("Debe ingresar el c\u00F3digo del expediente");
                return false;
            }
            destinoReq = "<select class='form-control' name='recibeRequerimiento[" + indexReq + "]'  style='width: 250px'>";
            destinoReq += "<option value = '0'>Seleccione un Item ...";
    <?php
    $destinoReq = $solRequerimientos->selectAll();
    foreach ($destinoReq as $cadaReq) {
        ?>
                destinoReq += "<option value = '<?= $cadaReq["id"] ?>'><?= $cadaReq["nombre"] ?>";
        <?php
    }
    ?>
            destinoReq += "</select>";

            tipoRequerimiento = "<select class='form-control' name='tipoReq[" + indexReq + "]'>";
            tipoRequerimiento += "<option value = '0'>Seleccione un Item ...";
    <?php
    $tipoReqs = $tipoRequerimientos->selectAll();
    foreach ($tipoReqs as $cadaTipo) {
        ?>
                tipoRequerimiento += "<option value = '<?= $cadaTipo["id"] ?>'><?= $cadaTipo["nombre"] ?>";
        <?php
    }
    ?>
            tipoRequerimiento += "</select>";

            txt = "<div id='campoReq" + indexReq + "' class='panel panel-danger'>" +
                    "<div class='panel-heading'>Requerimiento N&uacute;mero " + indexReq + " <a href='javascript:delDivReq(\"#campoReq" + indexReq + "\");'>[X]</a></div>" +
                    "<div class='panel-body'>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Tipo Requerimiento: </label>" +
                    "<div class='col-lg-4'>                " +
                    tipoRequerimiento +
                    "    </div>" +
                    " </div>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Destinatario: </label>" +
                    "<div class='col-lg-4'>" +
                    destinoReq +
                    "      </div>" +
                    "   </div>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Fecha Requerimiento:</label>" +
                    "<div class='col-lg-3'>" +
                    "<input id='dateTerm" + indexReq + "' type='text' class='form-control' name='fechaReq[" + indexReq + "]' placeholder='dd/mm/yyyy [hh24:mi]'>" +
                    "        </div>" +
                    "     </div>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Rango</label>" +
                    "<div class='col-lg-2'>" +
                    "           <label class='radio-inline'><input type='radio' id='displayD" + indexReq + "' disabled onchange='displayMD(" + indexReq + ", \"d\")' name='optradio" + indexReq + "'>Dias</label>" +
                    "           <label class='radio-inline'><input type='radio' id='displayM" + indexReq + "' disabled onchange='displayMD(" + indexReq + ", \"m\")' name='optradio" + indexReq + "'>Meses</label>" +
                    "             <select class='form-control' disabled id='selectMD" + indexReq + "'>" +
                    "               </select>" +
                    "           </div>" +
                    "        </div>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Fecha Vence</label>" +
                    "<div class='col-lg-3'>" +
                    "<input type='text' class='form-control' name='fechaVence[" + indexReq + "]' readonly id='fechaVence" + indexReq + "'>" +
                    "            </div>" +
                    "        </div>" +
                    "<div class='form-group'>" +
                    "<label class='control-label col-lg-3' for='email'>Detalle Requerimiento:</label>" +
                    "<div class='col-lg-3'>" +
                    "                <textarea rows='5' cols='60' class='form-control' name='detalleReq[" + indexReq + "]'></textarea>" +
                    "            </div>" +
                    "        </div>" +
                    "    </div>" +
                    "</div>";
            $("#div_main").append(txt);
            $("#dateTerm" + indexReq).datepicker({
                changeMonth: true,
                changeYear: true,
                beforeShowDay: checkHolidays
            });
            $("#dateTerm" + indexReq).datepicker("option", "showAnim", "slideDown");
            $("#dateTerm" + indexReq).datepicker("option", "dateFormat", "yy-mm-dd");
            $("#dateTerm" + indexReq).on('change', function () {

                var id = this.id.substr(this.id.length - 1);

                $("#displayD" + id).prop('disabled', false);
                $("#displayM" + id).prop('disabled', false);
                $("#selectMD" + id).prop('disabled', false);

            });
            $("#selectMD" + indexReq).on('change', function () {
                var valor = this.value;
                var id = this.id.substr(this.id.length - 1);
                var from = $("#dateTerm" + id).val().split("-");

                var fechai = new Date(from[0], from[1] - 1, from[2]);
                var fechaf = fechai;
                if (opcion == 'd') {
                    var valor2 = this.value;
                    for (var l = 1; l <= valor; l++) {
                        fechaf = new Date(from[0], from[1] - 1, from[2]);
                        fechaf.setDate(fechai.getDate() + parseInt(l));

                        if (!(checkHolidays(fechaf)[0])) {

                            valor = parseInt(valor) + 1;
                        }
                    }

                    $("#fechaVence" + id).val(fechaf.getFullYear() + "-" + (fechaf.getMonth() + 1) + "-" + fechaf.getDate());

                } else if (opcion == 'm') {

                    fechaf.setMonth(fechai.getMonth() + parseInt(valor));
                    fechaf.setMonth(fechai.getDate() + 1);
                    $("#fechaVence" + id).val(fechaf.getFullYear() + "-" + (fechaf.getMonth() + 1) + "-" + fechaf.getDate());
                }

            });

            indexReq++;
        }


        // Definici�n de variables para generar respuestas a requerimientos
        indexSol = 1;

        function delDivSol(nameDiv) {
            $(nameDiv).remove();
        }


        function insertDivSol() {
            if (ListaRequerimientos.trim() == "") {
                alert("No existen requerimientos por resolver para el expediente " + document.frm01.codigoExpediente.value.trim());
                return false;
            }

            destinoSol = "<select class='form-control' name='reqResuelve[" + indexSol + "]'  style='width: 350px'>";
            destinoSol += mostrarRequerimientos() + "</select>";

            txt = "	<br>&nbsp;<div id='campoSol" + indexSol + "' style='border:solid; border-color:#CCCCCC; border-width:thin;' >" +
                    "<div style='background:#FFE4E1'>Cumplimiento N&uacute;mero " + indexSol + ": &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:delDivSol(\"#campoSol" + indexSol + "\");'>[X]</a></div><hr size='0'>" +
                    "<table border=0>" +
                    "	<tr><td>Radicado al que Resuelve: </td><td>" + destinoSol + "<br>" +
                    "	<tr><td>Fecha de Cumplimiento:</td><td><input type='text' class='form-control' name='fechaSol[" + indexSol + "]' placeholder='dd/mm/yyyy [hh24:mi]'></td></tr>" +
                    "	<tr><td>Detalle Cumplimiento:</td>" +
                    "		<td><textarea rows='5' cols='60' class='form-control' name='detalleSol[" + indexSol + "]'></textarea></td></tr>" +
                    "	<tr><td>Satisfacci&oacute;n de la Respuesta:</td><td><select class='form-control' name='nivelSatisfaccion[" + indexSol + "]'><option value = '0'>Seleccione un Item ...<option value='Cumplido Totalmente'>Cumplido Totalmente<option value='Cumplido Parcialmente'>Cumplido Parcialmente<option value='No Cumplido'>No Cumplido</select></td></tr>" +
                    "</table>" +
                    "</div>";
            $("#div_main_sol").append(txt);
            indexSol++;
        }


    </script>

    <input type="hidden" name="idEmpresa" value="<?= $_SESSION['id_usuario'] ?>"/>
    <input type="hidden" name="idPlantilla" value="<?php echo $_GET["idPlantilla"] ?>"/>
    <input type="hidden" name="rutaImagen" value="<?php echo $rutaImagen ?>"/>	

    <div align="center"><strong><?php echo strtoupper($indices[0]["nombre_plantilla"]); ?></strong> </div>

    <hr size="1" />
    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Código de Expediente :</label>
        <div class="col-lg-3">
            <input type="text" class="form-control"  id="codigoExpediente" name="codigoExpediente" size="20" onchange="loadDocRequeridos(this.value)" value="<?= $codigoExp ?>"/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Número Radicado :</label>
        <div class="col-lg-3">
            <input type="text" class="form-control"  id="nroRadicado" name="nroRadicado" size="30"/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Fecha Radicado: </label>
        <div class="col-lg-3">
            <input type="text" class="form-control"  id="fechaRadicado" name="fechaRadicado" size="20" placeholder="dd/mm/yyyy [hh24:mi]"/> 	

        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Cantidad de Folios : </label>
        <div class="col-lg-3">
            <input type="text" class="form-control"  id="cantidadImagenes" name="cantidadImagenes" size="10"/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Referencia del Documento : </label>
        <div class="col-lg-3">
            <textarea name="docReferencia" rows="3" cols="45"/></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3" for="email">Entidad que Genera Documento: </label>
        <div class="col-lg-3">
            <select name="solRequerimiento" class="form-control"  style='width: 250px'>
                <?php
                $solicitaReq = $solRequerimientos->selectAll();
                echo "<option value = '0'>Seleccione un Item ...";
                foreach ($solicitaReq as $cadSolReq)
                    echo "<option value = '{$cadSolReq["id"]}'>" . ($cadSolReq["nombre"]);
                ?>
            </select>
        </div>
    </div>


    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">        

        <?php
        if (!empty($indices))
            foreach ($indices as $cadaIndice) {
                ?>
                <tr>
                    <td width="35%" align="left"><?= ucwords(strtolower($cadaIndice["nombre_indice"])); ?></td>
                    <td width="65%"><?= $template->generarCampoIndice($cadaIndice["id_indice"], $cadaIndice["tipo_dato"], $cadaIndice["lista_parametros"]); ?></td>
                </tr>
                <?php
            }
        ?>
        <tr>
            <td colspan="2"><hr size="1" /></td>
        </tr>

        <!-- Area para anexar requerimientos del documento -->
        <tr>
            <td colspan="2">
                <div style='background:#FFFFCC'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Asociar Requerimientos al Formulario:</b>
                </div>

                <div id="div_main"></div>
                <hr>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input  type="button" class="btn btn-default" value=" [+] Asociar Requerimiento" onclick="insertDivReq()" />
                <hr>
            </td>
        </tr>	
        <!-- fin requerimientos del documento -->

        <!-- Area para anexar que resuelve el documento -->
        <tr>
            <td colspan="2">
                <div style='background:#FFFFCC'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Asociar Cumplimientos a Requerimientos:</b>
                </div>

                <div id="div_main_sol"></div>
                <hr>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input  type="button" class="btn btn-default" value=" [+] Asociar Cumplimiento" onclick="insertDivSol()" />
                <hr>
            </td>
        </tr>
        <!-- fin de resueltos por el documento -->

        <!-- Selecci�n de las im�genes a digitalizar -->
        <tr>
            <td colspan="2">
                <div style='background:#FFFFCC'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Asociar Archivo PDF al Formulario:</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr size="1" /></td>
        </tr>			
        <tr>
            <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2"><hr size="1" /></td>
        </tr>
        <!-- Fin de Selecci�n de las im�genes a digitalizar -->			
        <tr>
            <td colspan="2"><div align="center">
                    <label>
                        <input  type="button" class="btn btn-default" name="Submit" value="Guardar Informaci&oacute;n" onclick="validarIndexamiento()"/>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="reset" class="btn btn-default" name="Submit2" value="Restablecer Formulario" />
                    </label>
                </div></td>
        </tr>
        <tr>
            <td colspan="2"><hr size="1" /></td>
        </tr>
    </table>					  
    <p>	

        <?php
    } else {
        ?>	
    <center><i><b>Debe seleccionar una plantilla de la selecci&oacute;n</b></i></center>
    <hr size="0">
    <?php
}
?>
