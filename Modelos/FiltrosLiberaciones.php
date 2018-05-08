<?php
/*
	Clase encargada de la administración de los filtros de liberación de expedientes
*/

	class FiltrosLiberaciones {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase FiltrosLiberaciones.\n";
				return 0;
			}
		}

		function insertAll($idUsuario, $queryArr) {
			$queryStr =  "select liberaciones_filtro_insert($1, $2) as result";			
			$params = array($idUsuario, $queryArr);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function selectFiltroLiberacionByUsuario($idUsuario) {
			$queryStr =  "
				select lf.filtro_query 
				from liberaciones_filtros_usuarios lf 
					inner join (select id_usuario_sgm, max(id) as id from liberaciones_filtros_usuarios group by id_usuario_sgm) lf2 on  lf.id=lf2.id
				where lf.id_usuario_sgm=$1 limit 1				
			";	
			
			$result = pg_query_params($this->conn, $queryStr, array($idUsuario));
			if (!$result) {
			  echo "Error al consultar filtro por usuario.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			return  $lista[0]["filtro_query"];
		}			
					
	}	
?>

