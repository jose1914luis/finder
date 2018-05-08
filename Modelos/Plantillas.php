<?php
/*
	Clase encargada de administrar la información relacionada con las Plantillas
	en el CMQ, para el caso serían los formularios de los expedientes
*/

	class Plantillas {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase Plantillas.\n";
				return 0;
			}
		}		

		function selectPlantillasAll() {
			$queryStr =  '
				select pl.id, pl.nombre, pl.detalle 
				from keeper.plantillas pl 
					order by pl.nombre
			';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar listado de todas las Plantillas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	

		function getRutaArchivoPlantilla($idPlantilla) {
			$queryStr =  '
				select keeper.getRutaPlantilla($1) as ruta_plantilla;
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idPlantilla));
			if (!$result) {
				echo "Error al consultar la ruta de archivo para plantilla.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return  $lista[0]["ruta_plantilla"];
		}
		
		function selectIndicesByPlantilla($idPlantilla) {
			$queryStr =  '
				select  
					pl.id as id_plantilla,
					pl.nombre as nombre_plantilla,
					pl.nro_indices,
					i.id as id_indice,
					i.posicion,
					i.requerido, 
					i.nombre as nombre_indice,
					td.nombre as tipo_dato,
					li.lista_parametros,
					li.es_multiple_seleccion
				from 	keeper.plantillas pl inner join keeper.indices i on (pl.id=i.id_plantilla)
						inner join keeper.tipos_datos td on (i.id_tipo_dato=td.id)
						left join keeper.listas_indices li on (i.id=li.id_indice)
				where pl.id = $1
				order by i.posicion
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idPlantilla));
			if (!$result) {
			  echo "Error al consultar listado de indices por plantilla.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function insertAll($plantilla) {
			if(!isset($plantilla["listOptions"]))
				$plantilla["listOptions"] = "";
			$queryStr		= "select keeper.plantillas_insert($1, $2, $3, $4) as result";		
			$params 		= array(utf8_encode($plantilla["nombrePlantilla"]), utf8_encode($this->procesarIndices($plantilla["fieldName"],$plantilla["fieldHelp"], $plantilla["fieldReq"], $plantilla["fieldType"], $plantilla["listOptions"])), utf8_encode($plantilla["detallePlantilla"]), utf8_encode($plantilla["clasificacionPlantilla"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista["0"]["result"];			
		}	

		function procesarIndices($nombreIndices, $helpIndices, $obligatorioIndices, $tiposDatos, $listasIndices) {
			$stringIndices = "";
		
			foreach ($nombreIndices as $key => $name) 
				$stringIndices .= (($obligatorioIndices[$key])?"°":"") . strtoupper($name) . ((!empty($listasIndices[$key]))? ">>" . ((trim(preg_replace('/(\n\r|\r\n)+/', ',', $listasIndices[$key])))) : "") . ":" . ((!empty($helpIndices[$key])) ? ($helpIndices[$key]) : "") . ":" .  ($tiposDatos[$key]) . "|";
			$stringIndices .= "-"; 
			
			return $stringIndices;
		}
				
		function generarCampoIndice($idIndice, $tipoCampo, $valorLista="") {
			//$valorLista = utf8_decode($valorLista);
			//$tipoCampo 	= utf8_encode($tipoCampo);
		
			if($tipoCampo=="TEXTO") {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice' size='40'>";
			} else if($tipoCampo=="TEXTO LARGO") {
				echo "<textarea name='indice_$idIndice' id='indice_$idIndice' cols='70' rows='4'></textarea>";
			} else if($tipoCampo=="ENTERO") {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice'>";
			} else if($tipoCampo=="DECIMAL") {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice'>";
			} else if($tipoCampo=="MONEDA") {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice'>";
			} else if(strpos(" ".$tipoCampo, "FECHA")>0) {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice' size='20' placeholder='dd/mm/yyyy [hh24:mi]'>";
			} else if($tipoCampo=="LISTA DE SELECCION") {
				echo "<select name='indice_$idIndice' id='indice_$idIndice' style='width: 250px'>";
				echo "<option value='0' selected>Seleccion...";
				$itemsMenu = split(',', $valorLista);
				foreach($itemsMenu as $cadaItem)
					if($cadaItem != "")
						echo "<option value='".trim($cadaItem)."'>".trim($cadaItem);
				echo "</select>";
			} else if($tipoCampo=="LISTA SELECCION MULTIPLE") {
				echo "<select name='indice_{$idIndice}[]' id='indice_$idIndice' size='7' multiple=true>";
				echo "<option value='0' selected>Seleccion...";
				$itemsMenu = split(',', $valorLista);
				foreach($itemsMenu as $cadaItem)
					if($cadaItem != "")
						echo "<option value='".trim($cadaItem)."'>".trim($cadaItem);
				echo "</select>";

			} else if($tipoCampo=="LISTA DE CHEQUEO") {
				$itemsMenu = split(',', $valorLista);
				$i=1;
				foreach($itemsMenu as $cadaItem) {
					if($cadaItem != "")
						echo "<input type='checkbox' name='indice_{$idIndice}[]' id='indice_$idIndice' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
						//echo "<input type='checkbox' name='indice_".$idIndice."_".$i."' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
					$i++;
				}
			} else if($tipoCampo=="EMAIL") {
				echo "<input type='text' name='indice_$idIndice' id='indice_$idIndice' size='50'>";
			} else if($tipoCampo=="LISTA CON RADIOBOTON") {
				$itemsMenu = split(',', $valorLista);
				foreach($itemsMenu as $cadaItem)
					if($cadaItem != "")
						echo "<input type='radio' name='indice_$idIndice' id='indice_$idIndice' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
			}
			echo "<hr size=1>";			
		}	
	}	
?>

