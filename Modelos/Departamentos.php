<?php
/*
	Clase encargada de la administración y gestión de los Departamentos de Colombia
*/

	class Departamentos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Departamentos.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, codigo_dane, nombre from departamentos order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Departamentos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

	}	
?>

