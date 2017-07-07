<?php
@session_start();

require_once("Acceso/Config.php");
require_once("Modelos/IndexacionesQueries.php");
require_once("Modelos/EstadisticasUsuarios.php");
require_once("Modelos/Usuarios_SGM.php");
require_once("/home/cmqpru/public_html/CMQ_Pruebas/IDB/Modelos/ControlPopups.php");

function createTable($listadoRegistros, $titulo, $clasificacion, &$listaPlacasQuery) {
    $generaURL = new ControlPopups();

    if (!empty($listadoRegistros)) {
        $nroRegistros = sizeof($listadoRegistros);
        $nroColumnas = sizeof($listadoRegistros[0]) + 1;

        $validate = new Usuarios_SGM();
        /*
          if( $validate->getTipoCuentaSGM($_SESSION["id_usuario"])=='CUENTA CORPORATIVA') 	{
          echo "<hr size=1><b>Formatos para descarga de archivos:</b>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitudes <a href='prospect.report.consulta.win.solicitudesExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de Solicitudes' height='35' width='35' border='0'></a>

          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T&iacute;tulos <a href='prospect.report.consulta.win.titulosExcelFormat.c.php' ><img src='Imagenes/excelDownload.jpg' title='Reporte de T&iacute;tulos' height='35' width='35' border='0'></a>
          <hr size='1'>";
          }
         */

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

    $controlUsuario = new EstadisticasUsuarios();
    $controlUsuario->setConsultaUsuario($_SESSION['usuario_sgm'], $_POST["txtBuscar"]);


    $res = new IndexacionesQueries();
    $listaSolicitudesResults = $res->selectSolicitudesByListaCampos($_POST["txtBuscar"]);
    $listaTitulosResults = $res->selectTitulosByListaCampos($_POST["txtBuscar"]);

    $listaPlacasQuery = "";
    echo "
			<html>
			<head>
				<link rel='stylesheet' href='Javascript/sigmin_account.css?v=2'>
			</head>
			<body>
				<div>&nbsp;</div>
		";
    ?>
    <div class='titleSite2' style='text-align:center'>Para acceder a mas información por favor registrese gratis.</div>
    <div>&nbsp;</div>
    <?php
    echo createTable($listaSolicitudesResults, "SOLICITUDES", "SOLICITUD", $listaPlacasQuery);
    echo "<p>&nbsp;</p>";
    echo createTable($listaTitulosResults, "TITULOS", "TITULO", $listaPlacasQuery);
    $listaPlacasQuery .= " 0=1 ";
    echo "
			<div>&nbsp;</div>
			<script>window.opener.parent.showMultiExpedientes(\"$listaPlacasQuery\");</script>";
    echo "</body>";

    //$_SESSION["myExcelSolicitudesFile"] 	= $listaSolicitudesResults;
    //$_SESSION["myExcelTitulosFile"] 		= $listaTitulosResults;		
}
