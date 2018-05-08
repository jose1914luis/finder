<?php
/*
	Clase encargada de la administración y gestión de los Tipos de Documentos de Identidad de las Personas del SIGMIN
*/

	class TiposIdentificacionPersonas {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase TiposIdentificacionPersonas.\n";
				return 0;
			}
		}
		
		function selectTiposNatural() {
			$queryStr =  "select id, tipo_identificacion as nombre from tipos_identificacion_persona where tipo_persona = 'NATURAL' order by tipo_identificacion";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar el tipo de identificacion para persona natural.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectTiposJuridica() {
			$queryStr =  "select id, tipo_identificacion as nombre from tipos_identificacion_persona where tipo_persona = 'JURIDICA' order by tipo_identificacion";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
				echo "Error al consultar el tipo de identificacion para persona juridica.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return  $lista;
		}
		
	}	
?>

