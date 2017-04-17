<?php
require_once("Acceso/Config.php");
require_once("Modelos/ReportGenerator.php");
require_once("Modelos/CreditosUsuarios.php");
require_once("Utilidades/procesarCoordenadasWKT.php");
require_once("Modelos/EstadisticasUsuarios.php");
require_once("Modelos/ProspectosBogSGM.php");
// require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php"); 	

$cred = new CreditosUsuarios();

if (empty($_SESSION['usuario_sgm']) || trim(@$_GET["placa"]) == "" || trim(@$_GET["clasificacion"]) == "")
    header("location: index.php");

/* 	
  $generaURL			= new ControlPopups();
  $visualizar			= $generaURL->getControlPopup($_GET["cod_acceso"]);

  if(empty($visualizar))
  header("location: index.php");
 */

$placa = $_GET["placa"];
$clasificacion = strtoupper($_GET["clasificacion"]);
$tipoExpediente = strtoupper($_GET["clasificacion"]);
$msgProceso = "";
$tabla = "";
$existeExpediente = 0;

// revisi�n de creditos
$verCoordenadas = 0;
$idProductoCred = 0;

$controlUsuario = new EstadisticasUsuarios();
$estado = $controlUsuario->setEstadisticasPlaca($_SESSION['usuario_sgm'], $placa);
$urlGetCoords = "?crd=expediente&placa=$placa&clasificacion=$clasificacion&credits=1";
$urlEstudioTec = "?crd=prospecto&placa=$placa&clasificacion=ESTUDIO_TECNICO_PROSPECTO&credits=1";
$urlDownloadShp = "?crd=dwn_expedientes_placa&placa=$placa&credits=1";
$urlDwnRepPry = "?crd=superposiciones_area&placa=$placa&credits=1";
$valorCredCoord = 1;
$valorCredET = 5;
$valorShapePry = 5;
$valorRepPry = 7;


$consulta = new ReportGenerator();
$servicio = "http://www.sigmin.co:8080/geoserver/CMQ/wms";

// para ver productos ya pagos
if (@$_GET["creditos_prod"] > 0) {
    $msgSistema = $cred->validarVigenciaCredito($_GET["creditos_prod"], $_SESSION['id_usuario']);
    if ($msgSistema == "OK") {
        $verCoordenadas = 1;
        $msgSistema = "";
    }
}

if ($clasificacion == 'SOLICITUD') {
    $cobertura = "solicitudes_cg";
    $tituloCobertura = "Solicitudes";
    $idProductoCred = 6;
} else if ($clasificacion == 'TITULO') {
    $cobertura = "titulos_cg";
    $tituloCobertura = "Titulos";
    $idProductoCred = 6;
} else if ($clasificacion == 'PROSPECTO') {
    $cobertura = "prospectos";
    $tituloCobertura = "Prospectos";
    $idProductoCred = 7;
    $urlGetCoords = "?crd=prospecto&placa=$placa&clasificacion=$clasificacion&credits=1";
    $urlDownloadShp = "?crd=dwn_shapes&placa=$placa&credits=1";
    $valorShapePry = 7;
} else if ($clasificacion == 'ESTUDIO_TECNICO_PROSPECTO') {
    $cobertura = "areas_superposiciones";
    $tituloCobertura = "Estudios_Tecnicos";
    $idProductoCred = 9;
} else {
    echo "<script>window.close();</script>";
}


// Validaci�n de cr�ditos consumidos
if (@$_GET["credits"] == 1 && $idProductoCred > 0) {
    $msgSistema = $cred->usarCreditos($_SESSION['id_usuario'], $idProductoCred, $placa);
    if ($msgSistema == "OK") {
        $verCoordenadas = 1;
        $msgSistema = "";
    }
}

if ($clasificacion == 'ESTUDIO_TECNICO_PROSPECTO' && !$verCoordenadas)
    echo "<script>alert('$msgSistema'); window.close();</script>";

// Procesamiento de expedientes, que pueden ser titulos, solicitudes, prospectos
if ($clasificacion == 'ESTUDIO_TECNICO_PROSPECTO')
    $consulta->ejecutarEstudiosTecnicosProspectos($_SESSION["idEmpresa"], $placa);
$expediente = $consulta->generarReporte($placa, $tipoExpediente);

//$accionPage = new SeguimientosUsuarios;
//$accionPage->generarAccion("Generacion de reporte de expediente. $tipoExpediente: $placa.");


if (!empty($expediente))
    $existeExpediente = 1; // Si existen resultados al expediente en cuesti�n


$info = $consulta->generarViewMap($placa, $clasificacion);
$coordenadas = $info["coordenadas"];
$areaPoly = $info["area_has"];
?>


<style type="text/css">
    <!--
    .Estilo1 {
        font-family: Verdana;
        font-size: 14px;
        color: #0E3384;
        font-weight: bold;
    }
    .tituloArea {
        color: #FFFFFF;
        font-weight: bold;
    }
    -->
</style>

<script src="http://dev.openlayers.org/OpenLayers.js"></script>
<script type="text/javascript" src="Javascript/validarFinder.js"></script>
<!-- this gmaps key generated for http://openlayers.org/dev/ -->
<script src="http://maps.google.com/maps/api/js?v=3.5&key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM&amp;sensor=false"></script>
<!-- <script src="http://maps.google.com/maps/api/js?v=3.5&amp;sensor=false"></script> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">

    var map, drawControls, polygonFeature, vectorLayer;
    var CONTAR_POLY = 0;
    var GLOBAL_POLY;

    OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
    OpenLayers.Util.onImageLoadErrorColor = "transparent";
    OpenLayers.ImgPath = "http://js.mapbox.com/theme/dark/";

    function init() {
        coordenadasResult = "<?php echo $coordenadas ?>";
        $.post('viewPoligonSIGMIN.php', {CoordenadasPry: coordenadasResult}, function (resp) {
            if (resp != "")
                eval(resp);
            else
                alert("falla al cargar los servicios geogr&aacute;ficos");
        });
    }
</script>

<img src="Javascript/images/loading_sgm.gif" width="140" height="140" id="loadingImage" style="display:none; top:50%; left:50%; z-index:2000; position:fixed !important; opacity: 0.65;" />
<script>$("#loadingImage").show();</script>  
<div class="container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Reporte</a></li>
        <li><a data-toggle="tab" href="#menu1">Indexar Documento</a></li>
        <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
        <li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
    </ul>


    <!--  VISUALIZACI�N DEL REPORTE DE DATOS DE ACUERDO AL TIPO DE EXPEDIENTE -->	

    <?php
   
    if ($tipoExpediente == 'SOLICITUD') {
        ?>
        <table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
            
            <tr>
                <td colspan="6" bgcolor="#ededed"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE EXPEDIENTE </div></td>
            </tr>
            <tr>
                <td width="64"><strong>Placa:</strong></td>
                <td width="149"><?php echo $expediente["placa"]; ?></td>
                <td width="120"><strong>Radicaci&oacute;n:</strong></td>
                <td width="177"><?php echo $expediente["fecha_radicacion"]; ?></td>
                <td width="131"><strong>Modalidad:</strong></td>
                <td width="235"><?php echo $expediente["modalidad"]; ?></td>
            </tr>
            <tr>
                <td><strong>Tipo:</strong></td>
                <td><?php echo $tipoExpediente; ?></td>
                <td><strong>Estado Jur&iacute;dico:</strong> </td>
                <td><?php echo $expediente["estado_juridico"]; ?></td>
                <td><strong>Grupo de Trabajo: </strong></td>
                <td><?php echo $expediente["grupo_trabajo"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Solicitante(s):</strong></td>
                <td colspan="4"><?php echo $expediente["solicitantes"]; ?></td>
            </tr>


            <?php
            if (@$_SESSION["usuario_sgm"] == "jmoreno" || @$_SESSION["usuario_sgm"] == "jecardenas") {
                ?>
                <tr>
                    <td colspan="2"><strong>Direcci&oacute;n de Correspondencia: </strong></td>
                    <td colspan="4"><?php echo $expediente["direccion_correspondencia"]; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Tel&eacute;fono de Contacto:</strong> </td>
                    <td colspan="4"><?php echo $expediente["telefono_contacto"]; ?></td>
                </tr>

                <?php
            }
            ?>		
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Mineral(es):</strong></td>
                <td colspan="4"><?php echo $expediente["minerales"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Municipio(s):</strong></td>
                <td colspan="4"><?php echo $expediente["municipios"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Sistema de Origen:</strong> </td>
                <td><?php echo $expediente["sistema_origen"]; ?></td>
                <td><strong>Descripci&oacute;n PA:</strong> </td>
                <td colspan="2"><?php echo $expediente["descripcion_pa"]; ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Area Solicitada Has: </strong></td>
                <td><?php echo $expediente["area_solicitada_ha"]; ?></td>
                <td><strong>Area Definitiva Has: </strong></td>
                <td colspan="2"><?php echo $expediente["area_def_has"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>

            <!-- Definici�n del �rea del expediente -->
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">POLIGONO DE LA SOLICITUD </div></td>
            </tr>
            <tr>
                <td colspan="6">	
            <center><div id="map" style='width: 100%; height: 500px; border: 0px;'></div></center>
            <hr size=1>
            </td>
            </tr>
            <!-- Fin: Definici�n del �rea del expediente -->
            <tr>
                <td colspan="6" align="center">
                    <?php
                    if (@$verCoordenadas == 0) {
                        ?>			
                        <a href="javascript:" title="Reporte con Coordenadas - <?= $valorCredCoord ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlGetCoords ?>')"/><img src="Imgs/icon_coordenates.png" width="70" height="70"/></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <?php
                    }
                    ?>				
                    <a href="javascript:" title="Descarga de Shape - <?= $valorShapePry ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlDownloadShp ?>')"/><img src="Imgs/icon_dwn_shape.jpg" width="70" height="70"/></a>
                </td>			
            </tr>			  
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">COORDENADAS DEL POLIGONO</div></td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;&nbsp;</td>
            </tr>			  
            <?php
            if (@$verCoordenadas == 1) {
                ?>	  
                <tr>
                    <td colspan="6">
                        <?php
                        $a = 0;
                        @$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);

                        if (!empty(@$areasPoly))
                            foreach ($areasPoly as $area) {
                                $exc = 0;
                                foreach ($area as $cadaArea) {
                                    $coords = explode(",", $cadaArea);
                                    $punto = 0;

                                    echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";

                                    $nroCoordenadas = sizeof($coords);
                                    foreach ($coords as $cadaCoord) {
                                        if ($punto == ($nroCoordenadas - 1))
                                            break;
                                        if ($punto == 0 && $exc == 0) {
                                            echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA " . ($a + 1) . "</b></div></td></tr>";
                                            echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                        } else if ($punto == 0 && $exc > 0) {
                                            echo "<tr><td colspan=3>AREA " . ($a + 1) . " : Exclusi&oacute;n " . ($exc) . "</td></tr>";
                                            echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                        }

                                        $xy = explode(" ", $cadaCoord);
                                        echo "<tr><td><center>" . ($punto + 1) . "</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

                                        $punto++;
                                    }
                                    $exc++;
                                }
                                $a++;
                                echo "</table>";
                            }
                        ?></td>
                </tr>			  
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="6" align="center"><i>No habilitada para este reporte</i></td>
                </tr>
                <?php
            }
            ?>	 
            <tr>
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
        </table>

        <?php
    }
    ?>

    <?php
    if ($tipoExpediente == 'TITULO') {
        ?>
        <table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
            
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE EXPEDIENTE </div></td>
            </tr>
            <tr>
                <td><strong>Placa:</strong></td>
                <td><?php echo $expediente["placa"]; ?></td>
                <td><strong>C&oacute;digo RMN: </strong></td>
                <td><?php echo $expediente["codigo_rmn"]; ?></td>
                <td><strong>C&oacute;digo Anterior: </strong></td>
                <td><?php echo $expediente["codigo_anterior"]; ?></td>
            </tr>
            <tr>
                <td width="112"><strong>Fecha Inscripci&oacute;n :</strong></td>
                <td width="172"><?php echo $expediente["fecha_inscripcion"]; ?></td>
                <td width="129"><strong>Fecha Terminaci&oacute;n: </strong></td>
                <td width="168"><?php echo $expediente["fecha_terminacion"]; ?></td>
                <td width="110"><strong>Modalidad:</strong></td>
                <td width="185"><?php echo $expediente["modalidad"]; ?></td>
            </tr>

            <tr>
                <td><strong>Tipo:</strong></td>
                <td><?php echo $tipoExpediente; ?></td>
                <td><strong>Estado Jur&iacute;dico:</strong> </td>
                <td><?php echo $expediente["estado_juridico"]; ?></td>
                <td><strong>Grupo de Trabajo: </strong></td>
                <td><?php echo $expediente["grupo_trabajo"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Titular(s):</strong></td>
                <td colspan="4"><?php echo $expediente["titulares"]; ?></td>
            </tr>

            <?php
            if (@$_SESSION["usuario_sgm"] == "jmoreno" || @$_SESSION["usuario_sgm"] == "jecardenas") {
                ?>
                <tr>
                    <td colspan="2"><strong>Direcci&oacute;n de Correspondencia: </strong></td>
                    <td colspan="4"><?php echo $expediente["direccion_correspondencia"]; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Tel&eacute;fono de Contacto:</strong> </td>
                    <td colspan="4"><?php echo $expediente["telefono_contacto"]; ?></td>
                </tr>

                <?php
            }
            ?>		
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Mineral(es):</strong></td>
                <td colspan="4"><?php echo $expediente["minerales"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Municipio(s):</strong></td>
                <td colspan="4"><?php echo $expediente["municipios"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Sistema de Origen:</strong> </td>
                <td><?php echo $expediente["sistema_origen"]; ?></td>
                <td><strong>Descripci&oacute;n PA:</strong> </td>
                <td colspan="2"><?php echo $expediente["descripcion_pa"]; ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Area Otorgada Has: </strong></td>
                <td><?php echo $expediente["area_otorgada_ha"]; ?></td>
                <td><strong>Area Definitiva Has: </strong></td>
                <td colspan="2"><?php echo $expediente["area_def_has"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>

            <!-- Definici�n del �rea del expediente -->
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">POLIGONO DEL TITULO MINERO </div></td>
            </tr>
            <tr>
                <td colspan="6">	
            <center><div id="map" style='width: 100%; height: 500px; border: 0px;'></div></center>
            <hr size=1>
            </td>
            </tr>
            <!-- Fin: Definici�n del �rea del expediente --> 
            <tr>
                <td colspan="6" align="center">
                    <?php
                    if (@$verCoordenadas == 0) {
                        ?>			
                        <a href="javascript:" title="Reporte con Coordenadas - <?= $valorCredCoord ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlGetCoords ?>')"/><img src="Imgs/icon_coordenates.png" width="70" height="70"/></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <?php
                    }
                    ?>				
                    <a href="javascript:" title="Descarga de Shape - <?= $valorShapePry ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlDownloadShp ?>')"/><img src="Imgs/icon_dwn_shape.jpg" width="70" height="70"/></a>
                </td>			
            </tr>
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">COORDENADAS DEL POLIGONO</div></td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;&nbsp;</td>
            </tr>		
            <?php
            if (@$verCoordenadas == 1) {
                ?>	  
                <tr>
                    <td colspan="6">
                        <?php
                        $a = 0;
                        @$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);

                        if (!empty(@$areasPoly))
                            foreach ($areasPoly as $area) {
                                $exc = 0;
                                foreach ($area as $cadaArea) {
                                    $coords = explode(",", $cadaArea);
                                    $punto = 0;

                                    echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";

                                    $nroCoordenadas = sizeof($coords);
                                    foreach ($coords as $cadaCoord) {
                                        if ($punto == ($nroCoordenadas - 1))
                                            break;
                                        if ($punto == 0 && $exc == 0) {
                                            echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA " . ($a + 1) . "</b></div></td></tr>";
                                            echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                        } else if ($punto == 0 && $exc > 0) {
                                            echo "<tr><td colspan=3>AREA " . ($a + 1) . " : Exclusi&oacute;n " . ($exc) . "</td></tr>";
                                            echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                        }

                                        $xy = explode(" ", $cadaCoord);
                                        echo "<tr><td><center>" . ($punto + 1) . "</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

                                        $punto++;
                                    }
                                    $exc++;
                                }
                                $a++;
                                echo "</table>";
                            }
                        ?></td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="6" align="center"><i>No habilitada para este reporte</i></td>
                </tr>
                <?php
            }
            ?>	  
            <tr>
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>  
        </table>

        <?php
    }
    ?>

    <?php
    if ($tipoExpediente == 'PROSPECTO') {
        ?>
        <table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
            
            <tr>
                <td colspan="5" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE PROSPECTO MINERO </div></td>
            </tr>
            <tr>
                <td><strong>Placa:</strong></td>
                <td><?php echo $expediente["placa"]; ?></td>
                <td><strong>Fecha Creaci&oacute;n: </strong></td>
                <td><?php echo $expediente["fecha_creacion"]; ?></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td colspan="5"><hr size="1" /></td>
            </tr>

            <tr>
                <td><strong>Sistema de Origen:</strong> </td>
                <td width="264"><?php echo $expediente["sistema_origen"]; ?></td>
                <td width="160"><strong>Area Definitiva:</strong></td>
                <td width="283"><?php echo $expediente["area_has"]; ?> Ha(s)</td>
            </tr>

            <tr>
                <td colspan="5"><hr size="1" /></td>
            </tr>  
            <tr>
                <td width="179"><strong>Municipio(s):</strong></td>
                <td colspan="3"><?php echo $expediente["municipios"]; ?></td>
            </tr>
            <tr>
                <td colspan="5"><hr size="1" /></td>
            </tr>

            <!-- Definici�n del �rea del expediente -->
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">POLIGONO DEL PROSPECTO </div></td>
            </tr>
            <tr>
                <td colspan="6">	
            <center><div id="map" style='width: 100%; height: 500px; border: 0px;'></div></center>
            <hr size=1>
            </td>
            </tr>
            <!-- Fin: Definici�n del �rea del expediente -->	
            <tr>
                <td colspan="6" align="center">
                    <?php
                    if (@$verCoordenadas == 0) {
                        ?>			
                        <a href="javascript:" title="Reporte con Coordenadas - <?= $valorCredCoord ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlGetCoords ?>')"/><img src="Imgs/icon_coordenates.png" width="70" height="70"/></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <?php
                    }
                    ?>				
                    <a href="javascript:" title="Reporte de &Aacute;rea Libre - <?= $valorCredET ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlEstudioTec ?>')"/><img src="Imgs/icon_rep_area_libre.jpg" width="70" height="70"/></a>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <a href="javascript:" title="Descarga de Shape de &Aacute;rea Libre - <?= $valorShapePry ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlDownloadShp ?>')"/><img src="Imgs/icon_dwn_shape.jpg" width="70" height="70"/></a>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <a href="javascript:" title="Descarga de Reporte de Superposiciones - <?= $valorRepPry ?> Cr&eacute;dito(s)" onclick="confirmaCredito('<?= $urlDwnRepPry ?>')"/><img src="Imgs/icon_reporte_sup.png" width="70" height="70"/></a>				
                </td>			
            </tr>	
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>

            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">COORDENADAS DEL POLIGONO </div></td>
            </tr>
            <?php
            if (@$verCoordenadas == 1) {
                ?>
                <tr>
                    <td colspan="5">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">		
                        <?php
                        $a = 0;
                        $areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);

                        foreach ($areasPoly as $area) {
                            $exc = 0;
                            foreach ($area as $cadaArea) {
                                $coords = explode(",", $cadaArea);
                                $punto = 0;

                                echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";

                                $nroCoordenadas = sizeof($coords);
                                foreach ($coords as $cadaCoord) {
                                    if ($punto == ($nroCoordenadas - 1))
                                        break;
                                    if ($punto == 0 && $exc == 0) {
                                        echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA " . ($a + 1) . "</b></div></td></tr>";
                                        echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                    } else if ($punto == 0 && $exc > 0) {
                                        echo "<tr><td colspan=3>AREA " . ($a + 1) . " : Exclusi&oacute;n " . ($exc) . "</td></tr>";
                                        echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                    }

                                    $xy = explode(" ", $cadaCoord);
                                    echo "<tr><td><center>" . ($punto + 1) . "</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

                                    $punto++;
                                }
                                $exc++;
                            }
                            $a++;
                            echo "</table>";
                        }
                    } else {
                        ?>
                <tr>
                    <td colspan="6" align="center"><i>No habilitada para este reporte</i></td>
                </tr>
                <?php
            }
            ?>  </td>
            </tr>	  
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>	
        </table>

        <?php
    }
    ?>

    <?php
    if ($tipoExpediente == 'ESTUDIO_TECNICO_PROSPECTO') {
        ?>
        <table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
            
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE PROSPECTO EVALUADO </div></td>
            </tr>
            <tr>
                <td width="64"><strong>Placa:</strong></td>
                <td width="149" colspan="2"><?php echo $expediente["placa"]; ?></td>
                <td width="177" colspan="2"><strong>Fecha Creaci&oacute;n :</strong></td>
                <td width="235"><?php echo $expediente["fecha_creacion"]; ?></td>
            </tr>

            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>


            <tr>
                <td colspan="2"><strong>Municipio(s) Area de Estudio:</strong></td>
                <td colspan="4"><?php echo $expediente["municipios"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Sistema de Origen:</strong> </td>
                <td colspan="4"><?php echo $expediente["sistema_origen"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Area Antes Estudio Ha:</strong> </td>
                <td width="120"><?php echo $expediente["area_def_has"]; ?></td>
                <td width="177"><strong>&Aacute;rea Despues Estudio Ha: </strong></td>
                <td colspan="2"><?php echo $expediente["area_def_estudio"]; ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>

            <!-- Definici�n del �rea del expediente -->
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">AREA DEFINITIVA DEL PROSPECTO </div></td>
            </tr>
            <tr>
                <td colspan="6">	
            <center><div id="map" style='width: 100%; height: 500px; border: 0px;'></div></center>
            <hr size=1>
            </td>
            </tr>
            <!-- Fin: Definici�n del �rea del expediente -->

            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">COORDENADAS ANTES DE ESTUDIO </div></td>
            </tr>
            <tr>
                <td colspan="6">
                    <?php
                    $a = 0;
                    $areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);

                    foreach ($areasPoly as $area) {
                        $exc = 0;
                        foreach ($area as $cadaArea) {
                            $coords = explode(",", $cadaArea);
                            $punto = 0;

                            echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";

                            $nroCoordenadas = sizeof($coords);
                            foreach ($coords as $cadaCoord) {
                                if ($punto == ($nroCoordenadas - 1))
                                    break;
                                if ($punto == 0 && $exc == 0) {
                                    echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA " . ($a + 1) . "</b></div></td></tr>";
                                    echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                } else if ($punto == 0 && $exc > 0) {
                                    echo "<tr><td colspan=3><center><b>AREA " . ($a + 1) . " : Exclusi&oacute;n " . ($exc) . "</b></center></td></tr>";
                                    echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                }

                                $xy = explode(" ", $cadaCoord);
                                echo "<tr><td><center>" . ($punto + 1) . "</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

                                $punto++;
                            }
                            $exc++;
                        }
                        $a++;
                        echo "</table>";
                    }
                    ?></td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
            <tr>
                <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">COORDENADAS DESPUES DE ESTUDIO</div></td>
            </tr>
            <tr>
                <td colspan="6">
                    <?php
                    $a = 0;
                    $areasPoly = procesarCoordenadasWKT($expediente["coordenadas_estudio"]);

                    foreach ($areasPoly as $area) {
                        $exc = 0;
                        foreach ($area as $cadaArea) {
                            $coords = explode(",", $cadaArea);
                            $punto = 0;

                            echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";

                            $nroCoordenadas = sizeof($coords);
                            foreach ($coords as $cadaCoord) {
                                if ($punto == ($nroCoordenadas - 1))
                                    break;
                                if ($punto == 0 && $exc == 0) {
                                    echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA " . ($a + 1) . "</b></div></td></tr>";
                                    echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                } else if ($punto == 0 && $exc > 0) {
                                    echo "<tr><td colspan=3><center><b>AREA " . ($a + 1) . " : Exclusi&oacute;n " . ($exc) . "</b></center></td></tr>";
                                    echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
                                }

                                $xy = explode(" ", $cadaCoord);
                                echo "<tr><td><center>" . ($punto + 1) . "</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

                                $punto++;
                            }
                            $exc++;
                        }
                        $a++;
                        echo "</table>";
                    }
                    ?>    </td>
            </tr>
            <tr>
                <td colspan="6"><hr size="1" /></td>
            </tr>
        </table>

        <?php
    }
    ?>	

    <?php
    if (@$msgSistema != "") {
        ?>
        <script>alert('<?= $msgSistema ?>');</script>
        <?php
    }

    if (@$_GET["credits"] == 1 && empty($_GET["fnd"])) { // Y no se encuentra en finder, si se encuentra en finder no debe recargar pagina padre
        ?>
        <script>window.opener.document.location.reload();</script>	
        <?php
    }
    ?>

    <script>init();
        $("#loadingImage").hide();</script>  
</div>