<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("Acceso/Config.php");
require_once("Modelos/IndexacionesQueries.php");
require_once("Modelos/EstadisticasUsuarios.php");
require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php");

function createTable_2($listadoRegistros, $titulo, $clasificacion, &$listaPlacasQuery) {
    $generaURL = new ControlPopups();

    if (!empty($listadoRegistros)) {
        $nroRegistros = sizeof($listadoRegistros);
        $nroColumnas = sizeof($listadoRegistros[0]) + 1;

     

        $tablaSol = "
				<table class='results' align='center' width='95%' border='1'>
				<caption=''>
					<div class='titleSite' style='text-align:center'>" . strtoupper($titulo) . " - N&uacute;mero de registros: $nroRegistros</div>
					<div>&nbsp;</div>
				</caption>";
        $tablaAll = $tablaSol;

        foreach ($listadoRegistros[0] as $k => $v) {
            // if($k != 'direccion_correspondencia' and $k != 'telefono_contacto')
            if (in_array($k, array('placa', 'modalidad', 'estado_juridico', 'grupo_trabajo', 'fecha_radicacion', 'codigo_rmn', 'codigo_anterior', 'fecha_inscripcion')))
                $tablaSol .= "<th  class='results' align='center'><b>" . strtoupper(str_replace("_", " ", $k)) . "</b></th>";
            $tablaAll .= "<th  class='results' align='center'><b>" . strtoupper(str_replace("_", " ", $k)) . "</b></td>";
        }

        $tablaSol .= "</tr>";
        $tablaAll .= "</tr>";

        for ($i = 0; $i < $nroRegistros; $i++) {
            // Almacenamiento del resultado de la consulta ya sea por titulo o por solicitud
            $listaPlacasQuery .= " s.placa='" . $listadoRegistros[$i]["placa"] . "' OR ";

            // $codAcceso = $generaURL->setControlPopup($listadoRegistros[$i]["placa"], $clasificacion);
            // $URL_Acceso = "http://www.sigmin.co/finder/reporteAreas.php?cod_acceso=$codAcceso";			
            // $enlace .= "&nbsp;<a href='javascript:' onclick=\"window.open('$URL_Acceso', 'pop3', 'width=600,height=500, resizable=yes, scrollbars=yes');\"><img src='Imagenes/reportIcon.jpg' border='0' width='30' height='30' title='Generaci&oacute;n de Reporte para ".$listadoRegistros[$i]["placa"]."'></a>";

            $URL_Acceso = "?crd=expediente&placa={$listadoRegistros[$i]["placa"]}&clasificacion=$clasificacion";


            foreach ($listadoRegistros[$i] as $k => $v) {
                //if($k != 'direccion_correspondencia' and $k != 'telefono_contacto')
                if (in_array($k, array('placa', 'modalidad', 'estado_juridico', 'grupo_trabajo', 'fecha_radicacion', 'codigo_rmn', 'codigo_anterior', 'fecha_inscripcion')))
                    $tablaSol .= "<td class='results'>" . ($v) . "</td>";
                $tablaAll .= "<td class='results'>" . ($v) . "</td>";
            }
            $tablaSol .= "</tr>";
            $tablaAll .= "</tr>";
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
    } else
        echo "<hr size=1><h3><strong>$titulo: </strong>No existen registros</h3>";
    return "";
}

// Inicio de variables de sesion para archivos de texto y para listado de solicitudes y titulos
$_SESSION["myExcelSolicitudesFile"] = "";
$_SESSION["myExcelTitulosFile"] = "";

if (!empty($_POST["txtBuscar"])) {

    $res = new IndexacionesQueries();
    $listaSolicitudesResults = $res->selectSolicitudesByListaCampos($_POST["txtBuscar"]);
    $listaTitulosResults = $res->selectTitulosByListaCampos($_POST["txtBuscar"]);

    $listaPlacasQuery = "";
    echo "
			
			
				<link rel='stylesheet' href='/Javascript/sigmin_account.css?v=2'>			
		";
    ?>
    <div class='titleSite2' style='text-align:center'>Para acceder a mas información por favor registrese gratis.</div>
    <div>&nbsp;</div>
    <?php
    echo createTable_2($listaSolicitudesResults, "SOLICITUDES", "SOLICITUD", $listaPlacasQuery);
    echo "<p>&nbsp;</p>";
    echo createTable_2($listaTitulosResults, "TITULOS", "TITULO", $listaPlacasQuery);
    $listaPlacasQuery .= " 0=1 ";
    echo "
			<div>&nbsp;</div>
                        <script>$( document ).ready(function() {
    showMultiExpedientes(\"$listaPlacasQuery\");
});</script>
			";
    
//
    //$_SESSION["myExcelSolicitudesFile"] 	= $listaSolicitudesResults;
    //$_SESSION["myExcelTitulosFile"] 		= $listaTitulosResults;		
}
