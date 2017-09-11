<link rel='stylesheet' href='/Javascript/sigmin_account.css?v='<?= $_VERSION ?>>
<?php
require_once './Acceso/SQL_EROS.php';


$eros = new SQL_EROS();

//get total 
$values = ['COUNT(*) as total'];
$total = $eros->select('v_todas_sol_til', $values, null, 0, 0, null, 'one');

$total = $total['total'];

$page = filter_input(INPUT_GET, 'pag');

$page = isset($page) ? $page : 0;

$_limite = 40;

// How many pages will there be
$pages = ceil($total / $_limite);

// Calculate the offset for the query
$offset = ($page - 1) * $_limite;

//cuantas se muestran a la derecha y a la izquierda
$mod = $page % 5;
if ($mod == 0) {
    $start = max(1, $page);
    $end = min($pages, $start + 5);
} else {
    $start = max(1, $page - $mod);
    $end = min($pages, $start + 5);
}

//selecionar primero las solicitudes
$values = ['tipo, placa, minerales, municipios, modalidad, estado_juridico, fecha::timestamp::date as fecha, titulares'];
//$order = ['nombre' => 'asc'];
$data = $eros->select('v_todas_sol_til', $values, null, $_limite, $offset, null, 'all');
?>
<div class="contenedor">
    <br>
    <center>
        <b>
        <h1 id="til1">Titulos y solicitudes mineras de Colombia</h1>    
        <h2 id="til2">Sistema de Gestion Minera de Colombia</h2>
        </b>
    </center>

    <div>

        <?php

        function createTableDirectorio($listadoRegistros, $clasificacion, &$listaPlacasQuery, $values) {
            $generaURL = new ControlPopups();

            if (!empty($listadoRegistros)) {
                $nroRegistros = sizeof($listadoRegistros);

                $nroColumnas = sizeof($listadoRegistros[0]);
                /*
                  if( $validate->getTipoCuentaSGM($_SESSION["id_usuario"])=='CUENTA CORPORATIVA') 	{
                  echo "<hr size=1><b>Formatos para descarga de archivos:</b>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitudes <a href='prospect.report.consulta.win.solicitudesExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de Solicitudes' height='35' width='35' border='0'></a>

                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T&iacute;tulos <a href='prospect.report.consulta.win.titulosExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de T&iacute;tulos' height='35' width='35' border='0'></a>
                  <hr size='1'>";
                  }
                 */

                $tablaSol = "<table class='results' align='center' width='95%' border='1'>";

                $tablaSol .= "<tr class='results'><th class='results' align='center'>REPORTE</th>";

                $tablaAll = $tablaSol;

                foreach ($listadoRegistros[0] as $k => $v) {

                    if (in_array($k, array('tipo', 'placa', 'minerales', 'municipios', 'modalidad', 'estado_juridico', 'fecha', 'titulares'), true)) {
                        $tablaSol .= "<th  class='results' align='center'><b>" . strtoupper(str_replace("_", " ", $k)) . "</b></th>";
                    }
                }



                $tablaSol .= "</tr>";
                $tablaAll .= "</tr>";

                for ($i = 0; $i < $nroRegistros; $i++) {
                    // Almacenamiento del resultado de la consulta ya sea por titulo o por solicitud
                    $listaPlacasQuery .= " s.placa='" . $listadoRegistros[$i]["placa"] . "' OR ";


                    $enlace = "<div style='display: flex;'><a class='btn btn-success' href='javascript:' onclick=\"informacion()\"><i class='fa fa-map-marker' aria-hidden='true'></i>     </a>";

                    $enlace .= "<a class='btn btn-primary' href='javascript:' onclick=\"informacion()\"><i class='fa fa-file-text' aria-hidden='true'></i></a></div>";


                    $tablaSol .= "<tr class='results'><td class='results' align='center'><b>$enlace</b></td>";
                    $tablaAll .= "<tr class='results'><td class='results' align='center'><b>$enlace</b></td>";

                    foreach ($listadoRegistros[$i] as $k => $v) {


                        //print_r($listadoRegistros[$i]);
                        //if($k != 'direccion_correspondencia' and $k != 'telefono_contacto')
                        if (in_array($k, array('tipo', 'placa', 'minerales', 'municipios', 'modalidad', 'estado_juridico', 'fecha', 'titulares'), true)) {
                            if($k == 'placa' || $k == 'minerales'){
                                $tablaSol .= "<td class='results'><a  href='/buscar/". $v ."' style='cursor:pointer'>" . ($v) . "</a></td>";
                            }  else {
                                $tablaSol .= "<td class='results'>" . ($v) . "</td>";
                            }
                            
                            //print_r($k . '   ' . $v);
                            //echo $tablaSol;
                        }

                        //$tablaAll .= "<td class='results'>" . ($v) . "</td>";
                        //break;
                    }

                    $tablaSol .= "</tr>";
                    $tablaAll .= "</tr>";
                    //break;
                }
                $tablaSol .= "</table>";
                $tablaAll .= "</table>";
                if ($clasificacion == "SOLICITUD")
                    $_SESSION["myExcelSolicitudesFile"] = $tablaAll;
                else
                    $_SESSION["myExcelTitulosFile"] = $tablaAll;

                //$listaPlacasQuery 	.= " 0=1 ";				
                // $tablaSol 			.= "<script>window.opener.parent.showMultiExpedientes(\"$listaPlacasQuery\",\"$clasificacion\");</script>";			

                return $tablaSol;
            } 
            return "";
        }

        echo createTableDirectorio($data, 'SOLICITUD', $listaPlacasQuery, $values);
        ?>
    </div>

    <div id="pagi" class="text-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">


                <?php
                $prevlink = ($page > 1) ? '<li><a href="/directorio/1" aria-label="Previous">&laquo;</a> </li> <li><a href="/directorio/' . ($page - 1) . '" aria-label="Previous">&lsaquo;</a></li>' : '<li class="disabled"><span aria-label="Previous">&laquo;</span> </li> <li class="disabled"><span aria-label="Previous">&lsaquo;</span></li>';

                $nextlink = ($page < $pages) ? '<li><a href="/directorio/' . ($page + 1) . '" aria-label="Next">&rsaquo;</a> </li> <li><a href="/directorio/' . $pages . '" title="Last page">&raquo;</a></li>' : '<li class="disabled"><span class="disabled">&rsaquo;</span> </li> <li class="disabled"><span aria-label="Next">&raquo;</span></li>';


                echo $prevlink;


                for ($j = $start; $j <= $end; $j++) {

                    echo '<li ' . (($j == $page) ? 'class="active"' : '' ) . '><a href="/directorio/' . $j . '">' . $j . '</a></li>';
                }
                echo $nextlink;
                ?>
            </ul>
        </nav>
    </div>

</div>

