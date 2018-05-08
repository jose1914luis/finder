<?php
/*
	Clase encargada de la administraci�n y gesti�n de los Tipos de Documento
*/

	class TiposDocumentos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexi�n en la clase Municipios.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nombre from tipos_documentos where id <= 6 order by id';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar tipos de documento.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
	}	

?>

