<?php
/*
	Clase encargada de administrar la información relacionada a los prospectos
	del CMQ
*/

	class ProspectosBogSGM {	
		var $conn;
		var $origen 		= 21897;  	// por default Gauss Bogotá
		var $wgs84 			= 4326; 	// Geográfico WGS84
		var $datumBogota 	= 4218; 	// Geográfico Datum Bogotá
		var $sistemasOrigen = array(
			"OESTE"  		=> 21896,		
			"BOGOTA" 		=> 21897,
			"ESTE"   		=> 21898,
			"ESTE-CENTRAL"	=> 21898,
			"ESTE-ESTE" 	=> 21899,
			""				=> 21897
		);
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de conexión en prospectos_sgm.\n";
				return 0;
			}					
		}

		function insertAll($pry, $idEmpresa) {
			$queryStr =  "select prospectos_sgm_insert($1, $2, $3) as result";
			$placa = $this->crearProspecto();
			
			$params = array($idEmpresa, $placa, trim($pry["coordenadasPry"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		
		
		function asignarSistemaOrigen($sOrigen) {
			return $this->sistemasOrigen[strtoupper($sOrigen)];
		}
		
		function crearProspecto() {
			$queryStr =  'SELECT crearProspecto() AS new_prospecto';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al generar nueva placa para prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["new_prospecto"];
		}
		
		function getProspecto() {
			return $this->placa;
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM PROSPECTOS_BOG_SGM ORDER BY placa';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar prospectos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectProspectoByEmpresa($IdEmpresa) {
			$queryStr =  'SELECT GID, PLACA FROM PROSPECTOS_BOG_SGM WHERE ID_EMPRESA = $1 ORDER BY 2';			
			
			$result = pg_query_params($this->conn, $queryStr, array($IdEmpresa));
			if (!$result) {
			  echo "Error al consultar prospectos por empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function get_CoordsPuntoByBuffer($coordsPunto, $radioAccion) {
			$queryStr =  "select get_CoordsPuntoByBuffer($1, $2) as result";			
			
			$params = array($coordsPunto, $radioAccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}

		function get_SuperposicionByNeighbor($idVecino) {
			$queryStr =  "
				select 
					s2.placa as expediente_superpone,
					s2.fecha_radicacion as fecha_radica_inscribe,
					getlinedatabyidsolicitud(s2.id, 'SOL_MINERALES') as minerales_area_superpone, 
					'SOLICITUD' as tipo_superposicion,
					getLineDataByIdSolicitud(s2.id,'SOL_PERSONAS') as titulares,
					s2.modalidad as modalidad_area_superpone,
					get_num(av.area/10000) as area_influencia,
					getMpiosDeptosByVecinos(av.id_vecino) as municipios_afectacion,
					get_num((ST_Area(ST_Intersection(av.the_geom, m.the_geom)))/10000) as area_superposicion,
					get_num((ST_Area(ST_Intersection(av.the_geom, m.the_geom))/ST_Area(av.the_geom))*100) as porcentaje_superpone
				from   analisis_vecinos av, solicitudes_cg_bog m inner join solicitudes s2 on (m.id_solicitud=s2.id)	
				where av.id_vecino=$1 and ST_Intersects(av.the_geom, m.the_geom)
				union
				select 
					s2.placa as expediente_superpone,
					s2.fecha_inscripcion as fecha_radica_inscribe,
					getLineDataByIdTitulo(s2.id, 'TIT_MINERALES') as minerales_area_superpone, 
					'TITULO' as tipo_superposicion,
					getLineDataByIdTitulo(s2.id,'TIT_PERSONAS') as titulares,
					s2.modalidad as modalidad_area_superpone,
					get_num(av.area/10000) as area_influencia,					
					getMpiosDeptosByVecinos(av.id_vecino) as municipios_afectacion,
					get_num((ST_Area(ST_Intersection(av.the_geom, m.the_geom)))/10000) as area_superposicion,
					get_num((ST_Area(ST_Intersection(av.the_geom, m.the_geom))/ST_Area(av.the_geom))*100) as porcentaje_superpone
				from   analisis_vecinos av, titulos_cg_bog m inner join titulos s2 on (m.id_titulo=s2.id)	
				where av.id_vecino=$1 and ST_Intersects(av.the_geom, m.the_geom)					
			";			
			
			$params = array($idVecino);	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta
			}	
			$lista = pg_fetch_all($result);	
			return  $lista;
		}
		
		function getCoordsProspectoByPlaca($placa) {
			$queryStr =  "select coalesce(get_CoordsProspectoByPlaca($1), '') as result";			
			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}

		
		function existePlaca($placa) {
			$queryStr =  'SELECT count(1) as existe FROM PROSPECTOS_BOG_SGM WHERE placa=$1 limit 1';			
			$params = array($placa);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la placa $placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	
		
		function existePlacaDelete($placa) {
			$queryStr =  '
				select count(1) as existe 
				from prospectos_sgm p
					left join prospectos_bog_sgm pb on (p.placa=pb.placa)
					left join prospectos_municipios_sgm pm on (p.placa=pm.placa)
				where p.placa = $1							
			';			
			$params = array($placa);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la placa $placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	

		function revisarGeometria($coords, $origen) {			
			$polyCorrecto = "";
			$queryStr =  "select ST_IsValidReason(ST_GeomFromText($1, $2)) as es_correcto";			
			$params = array($coords, $this->sistemasOrigen[$origen]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al validar coordenadas: Debe verificarse nuevamente la generación del polígono. $ERROR</td></tr></table>";
				$polyCorrecto = "Error de la operaci&oacute;n al validar coordenadas";
			}	else {
				$validacion = pg_fetch_all($result);	
				$polyCorrecto = $validacion[0]["es_correcto"];
				
				if($polyCorrecto=="Valid Geometry")
					$polyCorrecto = "";
			}

			return $polyCorrecto;
		}		
		
		function deleteTo($placa) {	
			$queryStr =  "delete from PROSPECTOS_BOG_SGM where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Coordenadas: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		

		function deleteWGS84To($placa) {	
			$queryStr =  "delete from PROSPECTOS where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Coordenadas WGS84: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}

		function deleteMunicipiosTo($placa) {	
			$queryStr =  "delete from PROSPECTOS_MUNICIPIOS where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar los municipios al prospecto $placa: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
		
		function deleteProspecto($placa) {
			$this->deleteTo($placa);
			$this->deleteWGS84To($placa);
			$this->deleteMunicipiosTo($placa);
			
			if($this->existePlacaDelete($placa))
				return 0; // Aún existe el polígono, no fue eliminado satisfactoriamente
			return 1;	
		}
		
		function updateAreaTo($placa) {	
			$queryStr =  "update PROSPECTOS_BOG set area=ST_Area(the_geom), perimetro=ST_Perimeter(the_geom) where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar area y perimetro del poligono: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}	

		function getCentroideWGS84($placa) {
			$queryStr =  "select ST_AsText(ST_centroid(the_geom)) as centroide from prospectos where placa='$placa'";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al obtener centroide del prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return explode(" ", substr($lista[0]["centroide"],6,-1)); 		
		}

		function getArea($placa) {
			$queryStr =  "select (area/10000) as area, perimetro from prospectos_bog where placa='$placa'";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al obtener centroide del prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return $lista[0]; 		
		}		
	}	
?>

