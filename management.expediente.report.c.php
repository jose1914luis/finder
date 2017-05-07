<?php
//	session_start();

require_once("Acceso/Config.php");
require_once("Modelos/DocumentManagement.php");
require_once("Modelos/SeguimientosUsuarios.php");
require_once("Modelos/Usuarios_SGM.php");
require_once("Modelos/Expedientes.php");

//validación de usuarios en CMQ
//	$validate 	= new Usuarios_SGM();
$expediente = new Expedientes();
//	
//	$Id_Empresa = $validate->validaAccesoPagina(@$_SESSION["usuario_sgm"], @$_SESSION["passwd_sgm"]);	
//	if(empty($Id_Empresa) || !$Id_Empresa) echo "<script> document.location.href = '{$GLOBALS ["url_error"]}';</script>";

$placa = "";
if (!empty($_GET["placa"])) {
    $documento = new DocumentManagement();
    //$idDoc 		= $_GET["idfrm"];
    $idDoc = 0;
    $listaMenu = $documento->selectFormsByPlaca($_GET["placa"]);
//                print_r($listaMenu);
    $placa = $_GET["placa"];

    if ($idDoc)
        $busqueda = $documento->selectFormsByIdDocumento($_GET["idfrm"]);

    $accionPage = new SeguimientosUsuarios;
    $accionPage->generarAccion("Consulta al Ebook del Expediente '$placa'");
}


$msgAcceso = "";
?>
<form name="documentos" id="documentos" target="digitalDocument" method="post" action="">
    <input type="hidden" name="txtPathFile" value="">
    <input type="hidden" name="idDocumento" value="">
    <div align='center' class='Estilo1'>DOCUMENTOS RELACIONADOS AL EXPEDIENTE <?php echo strtoupper($placa) ?></div>
    <?php
    foreach ($listaMenu as $itemLista) {

        $idDocumento = $itemLista["id_documento"];

        if (isset($idDocumento) && $idDocumento != "") {
            $dManagement = new DocumentManagement();
            $documentos = $dManagement->selectTextDocumentByFormDocument($idDocumento);
            $docPlantilla = $dManagement->selectDocPlantillaByFormDocument($idDocumento);
            $requerimientos = $dManagement->selectDocRequierenByFormDocument($idDocumento);
            $resueltos = $dManagement->selectDocResuelvenByFormDocument($idDocumento);
            ?>

            <table border='0' align='center' width='90%' class="table">				
                <tr class="danger">
                    <th align='center'>INDICE</th>
                    <th align='center'>INFORMACI&Oacute;N</th>
                </tr>



                <tr><td align='left'>DOCUMENTO</td><td align='justify'><a href="<?= $GLOBALS ["docDigital"] . $placa . "/" . $itemLista["nombre_pdf"] ?>" target="_blank">Abrir Documento</a></td></tr>
                <tr><td align='left'>GENERA DOCUMENTO</td><td align='justify'><?= ($docPlantilla["genera_documento"]) ?></td></tr>
                <tr><td align='left'>PLACA</td><td align='justify'><?= ($docPlantilla["placa"]) ?></td></tr>						
                <tr><td align='left'>N&Uacute;MERO RADICADO</td><td align='justify'><b><?= ($docPlantilla["numero_radicado"]) ?></b></td></tr>			
                <tr><td align='left'>FECHA RADICADO</td><td align='justify'><?= ($docPlantilla["fecha_radicado"]) ?></td></tr>			
                <tr><td align='left'>REFERENCIA DOCUMENTO</td><td align='justify'><?= ($docPlantilla["referencia"]) ?></td></tr>			
                <tr><td align='left'>N&Uacute;MERO DE FOLIOS</td><td align='justify'><?= ($docPlantilla["numero_folios"]) ?></td></tr>			


                <?php
                if (!empty($documentos))
                    foreach ($documentos as $cadaDocumento) {
                        echo "
					<tr>
						<td align='left'>" . utf8_decode($cadaDocumento["indice"]) . "</td>
						<td align='justify'>" . utf8_decode($cadaDocumento["dato"]) . "</td>
					</tr>			
				";
                    }

                $nroReq = 1;
                if (!empty($requerimientos))
                    foreach ($requerimientos as $cadaReq) {
                        $resuelto = ($cadaReq["resuelto_por"] != "NO RESUELTO") ? "<a href='http://www.sigmin.co/research/Keeper/management.expediente.report.c.php?placa={$docPlantilla["placa"]}&idfrm={$cadaReq["id_doc_resuelve"]}' target='_top'>{$cadaReq["resuelto_por"]}</a>" : $cadaReq["resuelto_por"];
                        ?>
                        <tr>
                            <td align='center' colspan='2'><HR></td>
                        </tr>			
                        <tr class="info">
                            <td align='center' colspan='2'><b>Requerimiento Nro. <?= $nroReq ?></b></td>
                        </tr>
                        <tr>
                            <td align='left'>TIPO DE REQUERIMIENTO</td>
                            <td align='justify'><?= ($cadaReq["tipo_requerimiento"]) ?></td>
                        </tr>			
                        <tr>
                            <td align='left'>FECHA DEL REQUERIMIENTO</td>
                            <td align='justify'><?= $cadaReq["fecha_requerimiento"] ?></td>
                        </tr>			
                        <tr>
                            <td align='left'>FECHA DE VENCIMIENTO</td>
                            <td align='justify'><?= $cadaReq["fecha_vencimiento"] ?></td>
                        </tr>						
                        <tr>
                            <td align='left'>REQUERIMIENTO ASIGNADO A</td>
                            <td align='justify'><?= ($cadaReq["requerido_a"]) ?></td>
                        </tr>			
                        <tr>
                            <td align='left'>DETALLE DEL REQUERIMIENTO</td>
                            <td align='justify'><?= ($cadaReq["descripcion"]) ?></td>
                        </tr>
                        <tr>
                            <td align='left'>RESUELTO POR</td>
                            <td align='justify'><b><?= ($resuelto) ?></b></td>
                        </tr>
                        <tr>
                            <td align='left'>ESTADO SATISFACCION</td>
                            <td align='justify'><?= $cadaReq["estado_satisfaccion"] ?></td>
                        </tr>			
                        <?php
                        $nroReq++;
                    }

                $nroRes = 1;
                if (!empty($resueltos))
                    foreach ($resueltos as $cadaRes) {
                        ?>
                        <tr>
                            <td align='center' colspan='2'><HR></td>
                        </tr>			
                        <tr bgcolor='dddddd'>
                            <td align='center' colspan='2' class='Estilo1'><b>Cumplimiento Nro. $nroRes</b></td>
                        </tr>
                        <tr>
                            <td align='left'>RADICADO RESUELTO</td>
                            <td align='justify'><b><a href='http://www.sigmin.co/research/Keeper/management.expediente.report.c.php?placa={$docPlantilla["placa"]}&idfrm={$cadaRes["id_doc_requiere"]}' target='_top'>{$cadaRes["radicado_requiere"]}</a></b></td>
                        </tr>			
                        <tr>
                            <td align='left'>FECHA DE CUMPLIMIENTO</td>
                            <td align='justify'><?= $cadaRes["fecha_cumplimiento"] ?></td>
                        </tr>			
                        <tr>
                            <td align='left'>DETALLE DEL CUMPLIMIENTO</td>
                            <td align='justify'><?= ($cadaRes["detalle_cumplimiento"]) ?></td>
                        </tr>
                        <tr>
                            <td align='left'>ESTADO SATISFACCI&Oacute;N</td>
                            <td align='justify'><?= ($cadaRes["estado_satisfaccion"]) ?></td>
                        </tr>
                        <?php
                        $nroRes++;
                    }



                echo "	
				<tr>
					<td align='center' colspan='2'><HR></td>
				</tr>			
			</table>";
            }
        }
        ?>

</form>