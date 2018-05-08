<?php
/*
	Clase encargada de administrar la información relacionada con los expedientes existentes en Finder
*/

	class Expedientes {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase Expedientes.\n";
				return 0;
			}
		}		

		function selectExpedientesByEmpresa($idEmpresa) {
			$queryStr =  '
				select id, id_empresa, placa, clasificacion, id_solicitud, id_titulo
					from keeper.expedientes
					where id_empresa=$1			
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar listado de los expedientes por empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectClasificacionByPlaca($placa) {
			$queryStr =  "
				select 	
					case when t.id is not null then 'TITULO' else 'SOLICITUD' end as clasificacion
				from titulos t full outer join solicitudes s on t.placa=s.placa 
				where t.placa=$1 or s.placa=$1 limit 1;
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al consultar clasificacion por codigo expediente.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  @$lista[0]["clasificacion"];
		}
		
		function insertExpediente($idEmpresa, $placa) {
			$queryStr =  "select keeper.expedientes_insert($1, $2) AS result";	
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa, $placa));
			if (!$result) {
			  echo "Error al consultar insercion de expediente.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["result"];
		}		
	}	
?>

