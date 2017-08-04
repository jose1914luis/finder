
<?php
@$valorCreditoExpediente = @$credito;
?>
<div id="table-wrapper">
    <!--<div style="overflow: auto; height: 100%; width: 100%">-->
    <form name="frmDescargas" method="POST" action="?mnu=descargas">
        <table width="95%" align="center">
            <tr>
                <td class="titleSite" align="center">Descarga de Shapes</td>
            </tr>
            <tr>
                <td><hr size="1"></td>
            </tr>
            <tr>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tableFonts">Ingrese las placas (de solicitudes, t&iacute;tulos y/o prospectos) a las que desea generar poligono <i>(separadas por coma, sin "Enter")</i>:</span> 
                </td>
            </tr>
            <tr>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="txtPlacas" rows="4" cols="100"><?= @$_POST["txtPlacas"] ?></textarea><br>
                    <hr size="1">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="valida_lista" value="Validar Placas" class="btn btn-success"/>
                    <hr size="1">
                </td>
            </tr>				
            <tr>
                <td>						
                    <?php
                    if (!empty($listaValidada)) {
                        $contarCreditos = 0;
                        ?>
                        <span class="tableFonts"><b>Resultado validaci&oacute;n del proceso</b></span><br>
                        <hr size="1">	

                        <table width="100%" align="center" class="results">

                            <thead>
                                <tr  class="results">	
                                    <th class="results"><b>Placa</b></th>
                                    <th class="results"><b>Tipo Expediente</b></th>
                                    <th class="results"><b>Estado Jur&iacute;dico</b></th>
                                    <th class="results"><b>Pol&iacute;gono Vigente</b></th>								
                                </tr>						
                            </thead>

                            <?php
                            foreach ($listaValidada as $cadaPlaca) {
                                if ($cadaPlaca["tipo_expediente"] != "Placa no definida")
                                    $contarCreditos++;
                                ?>

                                <tr class="results">	
                                    <td class="results"><?= $cadaPlaca["placa_usr"] ?></td>
                                    <td class="results"><?= $cadaPlaca["tipo_expediente"] ?></td>
                                    <td class="results"><?= $cadaPlaca["estado_juridico"] ?></td>
                                    <td class="results"><?= $cadaPlaca["poligono_vigente"] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr class="results">
                                <td colspan="4">
                            <center>
                                N&uacute;mero de Pol&iacute;gonos a generar: <b><?= $contarCreditos ?></b> 
                                <br/>
                                N&uacute;mero de cr&eacute;ditos a consumir: <b><?= $contarCreditos * $valorCreditoExpediente ?></b>&nbsp;&nbsp;
                                <hr size="0">
                                <input type="button" name="aceptar" value="Descargar Shape(s)" class="btn btn-success" onclick="enviar_descarga()"/>
                                <input type="hidden" name="nro_creditos" value="<?= $contarCreditos * $valorCreditoExpediente ?>"/>
                            </center>	
                    </td>
                </tr>

            </table>

            <?php
        }
        ?>
        </td>
        </tr>

        </table>

    </form>
</div>
