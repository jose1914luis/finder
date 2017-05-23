<script>
    function selFormulario(idPlantilla, codigoExp) {
        //console.log('viewFormularios.php?idPlantilla=' + idPlantilla + "&codigoExp =" + codigoExp);
//        idEmpresa = document.frm01.selEmpresa.options[document.frm01.selEmpresa.selectedIndex].value;
//        if (idEmpresa > 0)
        $("#template").load('viewFormularios.php?idPlantilla=' + idPlantilla + "&codigoExp=" + codigoExp);
//        else
//            alert("Debe seleccionar una Empresa");
    }
    ;
</script>


<form name="frm01" id="frm01" method="POST" enctype="multipart/form-data" class="form-horizontal">
    <input type="hidden" name="operacionForm" value="indexar.digitalizar"/>


    <div class="form-group">
        <label class="control-label col-sm-3" for="email"> Selección de la plantilla respectiva:</label>
        <div class="col-sm-5">
            <?php if (!empty($listaPlantillas) && !empty($listaEmpresas)) { ?>
                <select name="selPlantilla" class="form-control" id="selPlantilla" onchange="selFormulario(this.value, <?= "'" . $codigoExp . "'" ?>)" >
                    <option value="0" selected="selected">Seleccione la plantilla
                        <?php
                        foreach ($listaPlantillas as $cadaPlantilla)
                            echo "<option value='" . $cadaPlantilla["id"] . "' title='" . utf8_decode($cadaPlantilla["detalle"]) . "'>" . strtoupper(utf8_decode($cadaPlantilla["nombre"])) . "</option>\n";
                        ?>	                        
                </select>				
            <?php } else { ?>
                <center><h2>No Existen Plantillas en el Sistema. Primero debe generar plantillas en Keeper</h2></center>
            <?php } ?>		
        </div>
    </div>

    <table border="0" align="center" width="100%">

        <tr>
            <td width="100%" align="left" valign="top">

                <div id="template">							
                    <center><i><b>Debe seleccionar una plantilla de la selecci&oacute;n</b></i></center>
                    <hr size="0">
                </div>	
            </td>
        </tr>
    </table>
</form>


