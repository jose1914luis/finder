<?php
/*
	Clase encargada del análisis de vecinos más cercanos
*/

	class AnalisisVecinos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase AnalisisVecinos.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id_vecino, id_empresa, fecha_creacion, sistema_origen, area, perimetro, the_geom from analisis_vecinos order by 1';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Analisis de Vecinos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectIdVecino() {	
			$queryStr =  "select nextval('analisis_vecinos_seq') as id_vecino";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Id de Vecino.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id_vecino"];
		}		
		
		function insertAll($idVecino, $idEmpresa, $sistemaOrigen, $coordenadas) {
			$queryStr =  "select analisisvecinos_insert($1, $2, $3, $4) as result";			
			$params = array($idVecino, $idEmpresa, $sistemaOrigen, $coordenadas);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}					
	}	
?>

