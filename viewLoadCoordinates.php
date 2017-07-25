<?php
require_once("Acceso/Config.php"); // Definici�n de las variables globales	
require_once("Modelos/ReportGenerator.php"); // Definici�n de las variables globales	
require_once("Modelos/CreditosUsuarios.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


/* 	
  print_r($_FILES);
  echo "<hr>";
  print_r($_POST);
  echo "<hr>";
  var_dump($_FILES);
  echo "<hr>";
 */

if (@$_POST["sistemaOrigen"]) {
    if (@$_FILES["fileToUpload"]["tmp_name"]) {
        $archivo = file($_FILES["fileToUpload"]["tmp_name"]);
        $sistemaOrigen = $_POST["sistemaOrigen"];
        $coordenadas = "";
        $nro_coordenadas = count($archivo);

        $archivoStr = implode(",", $archivo);
        $contarDosPuntos = substr_count($archivoStr, ':', 0);
        $contarPuntoComa = substr_count($archivoStr, ';', 0);

        if ($contarDosPuntos == $nro_coordenadas && $contarPuntoComa == $nro_coordenadas) {

            if (trim($archivo[0]) == ($archivo[$nro_coordenadas - 1]))
                $nro_coordenadas--;

            for ($i = 0; $i < $nro_coordenadas; $i++) {
                $coords = explode(":", str_replace(";", "", $archivo[$i]));
                $coordenadas .= $coords[1] . " " . $coords[0] . ", ";
            }

            $coords = explode(":", str_replace(";", "", $archivo[0]));
            $coordenadas .= $coords[1] . " " . $coords[0];
            $coordenadas = "MULTIPOLYGON(((" . $coordenadas . ")))";
            $coordenadas = str_replace("\n", "", $coordenadas);
            $coordenadas = str_replace("\r", "", $coordenadas);

            $transform = new ReportGenerator();

            $coordenadas_js = $coordenadas;
            if ($sistemaOrigen != 'WGS84') {
                $coordenadas_js = $transform->get_GaussToWGS84($coordenadas, $sistemaOrigen);
            }

            // calculo del �rea a evaluar
            $cred = new CreditosUsuarios();
            $areaCalculada = $cred->getArea($coordenadas_js);
            ?>		

            $("#infoAL").val( "<?= $areaCalculada ?> Hect.");

            //Eliminando pol�gonos generados manualmente;
            GLOBAL_POLY.removeAllFeatures();

            document.forms["free"].coordenadasPry.value = "<?php echo $coordenadas_js; ?>";
            document.forms["alarm"].coordenadasPry.value = "<?php echo $coordenadas_js; ?>";
            vectorLayer.removeAllFeatures();
            polygonFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas_js ?>").transform(
            displayProjection,
            projection
            ));
            polygonFeature.attributes = {
            placa: "<?php echo utf8_encode("Area de Inter�s") ?>"
            };		
            vectorLayer.addFeatures([polygonFeature]);
            bounds = vectorLayer.getDataExtent();
            map.zoomToExtent(bounds);			

            <?php
        } else {
            echo "	
					alert('Formato de archivo de texto incorrecto, revise la estructura del archivo');
					document.forms['frmCoordinates'].fileToUpload.focus();
				";
        }
    } else {
        echo "	
				alert('Seleccione un archivo de texto v&#225;lido');
				document.forms['frmCoordinates'].fileToUpload.focus();
			";
    }
} else
    echo "	
			alert('Seleccione un Sistema de Coordenadas');
			document.forms['frmCoordinates'].selGeoSystem.focus();
			document.forms['frmCoordinates'].selGeoSystem.select();
		";
?>