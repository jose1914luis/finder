<?php
/*
	Clase encargada de la administración y gestión de los Municipios de Colombia
*/

	class Municipios {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Municipios.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, codigo_dane, id_departamento, nombre from municipios order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Municipios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectByIdDepto($IdDepto) {
			$queryStr =  'select id, codigo_dane, id_departamento, nombre from municipios where id_departamento=$1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($IdDepto));
			if (!$result) {
			  echo "Error al consultar Municipios por Departamento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		

	}	
?>

