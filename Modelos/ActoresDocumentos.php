<?php
/*
	Clase encargada de desplegar los posibles tipos de formularios que se puedan presentar
*/

	class ActoresDocumentos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Actores Documentos.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nombre from keeper.actores_documentos where id_padre is not null order by 1';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar los generadores de documentos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
	}	
?>

