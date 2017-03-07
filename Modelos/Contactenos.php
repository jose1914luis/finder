<?php
/*
	Clase encargada de la administración y gestión de las sugerencias remitidas por nuestros usuarios
*/

	class Contactenos {
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Contactenos.\n";
				return 0;
			}
		}
	
		function selectAll() {
			$queryStr =  'select id, id_usuario, observacion, fecha from servicios.contactenos order by 1';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar listado de sugerencias.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($idUsuario, $observacion) {
			$queryStr =  "Select servicios.contactenos_insert($1, $2) as result";			
			
			$params = array($idUsuario, $observacion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista[0]["result"];
			}
		}	

	}	
?>

