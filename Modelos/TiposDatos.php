<?php
/*
	Clase encargada de presentar los tipos de datos existentes
*/

	class TiposDatos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase TiposDatos.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nombre, clasificacion from keeper.tipos_datos order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar tipos de datos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
	}	
?>

