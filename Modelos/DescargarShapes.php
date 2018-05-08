<?php
/*
	Función que realiza las operaciones de descargar archivos de 
*/

	class DescargarShapes {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase DescargarShapes.\n";
				return 0;
			}
		}
		
		function getShapeListaExpedientesBog($lista, $session) {
			$queryStr 	=  "select getShapeListaExpedientesBog($1, $2) as result";			
			$params 	= array($lista, $session);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	

			return $lista[0]["result"];				
		}
		
		function validacionExpedientesDescargar($listaExpedientes) {
			$queryStr 	=  "
				select 
					t2.placa_usr, 
					case when ce.tipo_expediente is not null then ce.tipo_expediente
					else 'Placa no definida' 
					end as tipo_expediente,
					estado_juridico,
					poligono_vigente
				from (select (regexp_split_to_table($1,',')) as placa_usr) t2
					left join servicios.v_clasificacion_expedientes_geo ce
						on t2.placa_usr=ce.placa
			";			
			$params 	= array($listaExpedientes);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	

			return $lista;				
		}	
		
		function contarExpedientesDescargar($listaExpedientes) {
			$queryStr 	=  "
				select 
					count(1) as total
				from (select (regexp_split_to_table($1,',')) as placa_usr) t2
					inner join servicios.v_clasificacion_expedientes_geo ce
						on t2.placa_usr=ce.placa	
			";			
			$params 	= array($listaExpedientes);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	

			return $lista[0]["total"];				
		}	
		
		function borrarCaracteres($string) {
		   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
		   return preg_replace('/[^A-Za-z0-9\-\,]/', '', $string); // Removes special chars.
		}		
		
	}	
?>

